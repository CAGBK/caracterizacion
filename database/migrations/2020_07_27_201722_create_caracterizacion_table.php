<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaracterizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caracterizacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('por_responsabilidades_es_indispensable_su_trabajo_presencial');
            $table->string('por_que')->nullable();
            $table->time('horaEntrada');
            $table->time('horaSalida');
            $table->string('dias_laborales');
            $table->string('trabajo_en_casa');
            $table->string('viabilidad_por_caracterizacion')->nullable();
            $table->string('revision1')->nullable();
            $table->string('revision2')->nullable();
            $table->string('observacion_cambios_de_estado')->nullable();
            $table->string('notas_comentarios_ma_andrea_leyva')->nullable();
            $table->string('envio_de_consentimiento')->nullable();
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
        Schema::dropIfExists('caracterizacion');
    }
}