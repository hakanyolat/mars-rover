<?php

use App\Models\Rover;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRovers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rovers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('x');
            $table->unsignedInteger('y');
            $table->char('direction');
            $table->string('state')->default(Rover::Ready);
            $table->string('queue')->nullable();
            $table->unsignedBigInteger('plateau_id');
            $table->foreign('plateau_id')->references('id')->on('plateaux');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rovers');
    }
}
