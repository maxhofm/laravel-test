<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendReplyRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Jobs\SendEmailJob;
use App\Models\File;
use App\Models\Order;
use App\Models\OrderStatus;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Получение списка заявок
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $orders = Order::with(['client', 'status', 'file'])->orderBy('created_at')->get();
        return view('order.index', compact('orders'));
    }

    /**
     * Создание новой заявки
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Создание новой заявки
     *
     * @param StoreOrderRequest $request
     * @return RedirectResponse
     */
    public function store(StoreOrderRequest $request, ): RedirectResponse
    {
        $success = true;

        // Проверка на возможность создания заявки по времени
        // (Прошел 1 деня с момента создания последней заявки)
        $clientId = auth()->user()->getAuthIdentifier();
        $order = Order::orderByDesc('created_at')
            ->where('client_id', $clientId)
            ->where('created_at', '>', Carbon::now()->subDays(1)->toDateTimeString())
            ->first();
        // TODO: !epmty()
        if (empty($order)) {
            Session::flash(
                'alert-warning',
                'Вы можете создавать новую заявку только спустя сутки с момента создания предыдущей'
            );
            $success = false;
        }

        // Проверка на наличие в форме и последующее сохранение файла
        $file = null;
        if ($request->hasFile('file') && $success) {
            $fileName = Storage::disk('public')->put('', request()->file('file'));
            if ($fileName) {
                try {
                    $file = File::create([
                        'path' => $fileName,
                    ]);
                } catch (Exception $e) {
                    // Удаляем файл физически, если не удалось сохранить в базу
                    Storage::delete($fileName);
                    Log::error($e->getMessage());
                    Session::flash(
                        'alert-warning',
                        'Не удалось сохранить файл'
                    );
                    $success = false;
                }
            }
        }

        // Если прошли валидацию и удалось сохранить файл при отправке, создаем заявку
        if ($success) {
            try {
                Order::create([
                    'title' => $request->title,
                    'text' => $request->text,
                    'client_id' => auth()->user()->getAuthIdentifier(),
                    'file_id' => $file ? $file->id : null,
                ]);

                SendEmailJob::dispatchAfterResponse($order);
                Session::flash('alert-success', 'Заявка успешно отправлена');
            } catch (Exception $e) {
                // Удаляем файл и запись в базе, если не удалось создать заявку
                if ($file) {
                    $file->delete();
                }
                Log::error($e->getMessage());
                Session::flash(
                    'alert-warning',
                    'Не удалось сохранить заявку'
                );
            }
        }

        return redirect()->back();
    }

    /**
     * Страница ответа на заявку
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function reply(int $id)
    {
        return view('order.reply', ['order' => Order::find($id)]);
    }

    /**
     * Отправка ответа на заявку
     *
     * @param SendReplyRequest $request
     * @return RedirectResponse
     */
    public function sendReply(SendReplyRequest $request): RedirectResponse
    {
        $flashType = 'warning';
        $flashMsg = 'Не удалось найти заявку';

        // Ищем заявку по id
        $order = Order::find($request->id);
        if (!empty($order)) {
            $flashType = 'success';
            $flashMsg = 'Ответ отправлен';
            try {
                // Назначаем заявку на менеджра и сохраняем ответ
                $order->reply = $request->reply;
                $order->status_id = OrderStatus::STATUS_HANDLED;
                $order->manager_id = auth()->user()->getAuthIdentifier();
                $order->updated_at = Carbon::now()->toDateTimeString();
                $order->save();
            } catch (Exception $e) {
                $flashType = 'danger';
                $flashMsg = 'Не удалось сохранить заявку';
                Log::error($e->getMessage());
            }
        }

        Session::flash('alert-' . $flashType, $flashMsg);
        return redirect('orders');
    }
}
