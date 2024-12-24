<?php

use App\Models\Brand;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Brand::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('sku')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->text('image')->nullable();
            $table->unsignedBigInteger('quantity');
            $table->decimal('price', 10);
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->enum('type', ['deliverable', 'downloadable'])->default('deliverable');
            $table->timestamp('published_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
