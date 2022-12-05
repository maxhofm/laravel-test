<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->char('title', 100);
            $table->text('text');
            $table->text('reply')->nullable();
            $table->foreignId('file_id')->nullable()->constrained('files');
            $table->foreignId('client_id')->constrained('users');
            $table->foreignId('manager_id')->nullable()->constrained('users');
            $table->foreignId('status_id')->default(1)->constrained('order_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
