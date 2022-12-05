<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Jobs\SendEmailJob;
use App\Models\File;
use App\Models\Order;
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
