<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialBillingMasivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_billing_masives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suscripcion_id')
                ->constrained('suscripciones')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->integer('expected_quantity')->default(0);
            $table->integer('real_quantity')->default(0);
            $table->enum('status',['Finalizada','Error','En Proceso'])->default('En Proceso');
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
        Schema::dropIfExists('historial_billing_masives');
    }
}
