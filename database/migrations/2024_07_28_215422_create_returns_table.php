<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_from_association_id')->constrained()->onDelete('cascade'); // العلاقة مع جدول الاستلام من الجمعية
            $table->enum('return_to', ['association', 'institution'])->default('association'); // تحديد ما إذا كان المرتجع للجمعية أو المؤسسة
            $table->foreignId('association_id')->nullable()->constrained('users'); // قد يكون فارغاً إذا كان المرتجع للمؤسسة
            $table->decimal('defective_quantity_due_to_coagulation', 8, 2);
            $table->decimal('defective_quantity_due_to_impurities', 8, 2);
            $table->decimal('defective_quantity_due_to_density', 8, 2);
            $table->decimal('defective_quantity_due_to_acidity', 8, 2);
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
}
