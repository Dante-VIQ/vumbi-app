// database/migrations/xxxx_xx_xx_xxxxxx_create_curated_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curated_items', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'hotel', 'tour', 'flight', 'offer'
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('price')->nullable();       // display price, e.g. "From $120"
            $table->string('image_url')->nullable();
            $table->string('link_url');                // booking/affiliate/own link
            $table->json('destinations')->nullable();  // ["Maasai Mara", "Nairobi"]
            $table->json('tags')->nullable();          // ["safari", "luxury"] for future interest matching
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curated_items');
    }
};