<?php

use App\Enums\GameStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number');
            $table->integer('length')->default(3);
            $table->integer('try')->default(0);
            $table->enum('status_enum',array_column(GameStatusEnum::cases(),'value'))->default(GameStatusEnum::Doing->value);
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('end_at')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
