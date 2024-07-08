<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('profiles', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('firstname');
    //         $table->string('lastname');
    //         $table->date('date_of_birth');
    //         $table->string('gender');
    //         $table->string('address');
    //         $table->integer('phonenumber');
    //         $table->string('Education');
    //         $table->string('skills');
    //         // $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
    //         $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    //         $table->timestamps();
    //     });
    // }


    public function up(): void
{
    Schema::create('profiles', function (Blueprint $table) {
        $table->id();
        $table->string('firstname');
        $table->string('lastname');
        $table->date('date_of_birth');
        $table->enum('gender', ['male', 'female']);
        $table->string('address');
        $table->bigInteger('phonenumber');
        $table->string('education');
        $table->string('skills');
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->unique(['user_id', 'gender']);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
