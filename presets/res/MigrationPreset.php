<?='<?php'?>


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class <?=$className?> extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<?php if ($withContent): ?>
<?php if ($mode === 'create'): ?>
        Schema::create('<?=$table?>', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
<?php else: ?>
        Schema::table('<?=$table?>', function (Blueprint $table) {
            //
        });
<?php endif?>
<?php else: ?>
        //
<?php endif?>
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
<?php if ($withContent): ?>
<?php if ($mode === 'create'): ?>
        Schema::dropIfExists('<?=$table?>');
<?php else: ?>
        Schema::table('<?=$table?>', function (Blueprint $table) {
            //
        });
<?php endif?>
<?php else: ?>
        //
<?php endif?>
    }
}