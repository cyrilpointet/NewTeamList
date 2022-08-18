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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')
                ->references('id')
                ->on('teams')->onDelete('cascade');
            $table->string('user_email');
            $table->boolean('is_from_team')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
};
