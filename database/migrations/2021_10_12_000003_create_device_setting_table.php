<?php

use Aldeeb\MultiAgents\Models\Device;
use Aldeeb\MultiAgents\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_device_setting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('setting_id');
            $table->foreign('device_id')->references('id')->on('ma_devices')->cascadeOnDelete();
            $table->foreign('setting_id')->references('id')->on('ma_settings')->cascadeOnDelete();
            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ma_device_setting');
    }
}
