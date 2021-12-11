<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_devices', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('token', 64)->unique();
            $table->string('agent');
            $table->nullableMorphs('ownerable');
            $table->timestamp('last_used_at')->useCurrent();
            $table->timestamp('expired_at')->nullable();
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
        Schema::dropIfExists('ma_devices');
    }
}
