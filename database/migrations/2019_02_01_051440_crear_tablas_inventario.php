<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablasInventario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      //TABLA DE UNIDADES
      Schema::create('unidades', function (Blueprint $t) {
          $t->increments('idregistro');
          $t->string('nombre',50);
          $t->mediumtext('descripcion')
            ->nullable();
          $t->timestamp('creado')
            ->default(DB::raw('CURRENT_TIMESTAMP'))
            ->nullable();
          $t->timestamp('actualizado')
            ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
            ->nullable();
      });
      //TABLA DE TIPOS PRODUCTOS
      Schema::create('tipos_productos', function (Blueprint $t) {
          $t->increments('idregistro');
          $t->string('nombre',50);
          $t->mediumtext('descripcion')
            ->nullable();
          $t->timestamp('creado')
            ->useCurrent()
            ->nullable();
          $t->timestamp('actualizado')
            ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
            ->nullable();
      });
      //TABLA DE IMPUESTOS
      Schema::create('impuestos', function (Blueprint $t) {
          $t->increments('idregistro');
          $t->string('nombre', 50);
          $t->decimal('valor', 10, 4);
          $t->timestamp('creado')
            ->useCurrent()
            ->nullable();
          $t->timestamp('actualizado')
            ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
            ->nullable();
      });

      //TABLA DE PRODUCTOS
      Schema::create('productos', function (Blueprint $t) {
          $t->increments('idregistro');
          $t->string('codigo',15);
          $t->string('nombre',30);
          $t->mediumtext('descripcion')
            ->nullable();
          $t->integer('idtipo')->unsigned();
          $t->integer('idunidad')->unsigned();
          $t->decimal('cantidad_minima',10,4);
          $t->decimal('precio',10,4);
          $t->boolean('perecedero');
          $t->boolean('grabado');
          $t->timestamp('creado')
            ->useCurrent()
            ->nullable();
          $t->timestamp('actualizado')
            ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
            ->nullable();
      });
      Schema::table('productos', function (Blueprint $t) {
          $t->foreign('idtipo')
            ->references('idregistro')
            ->on('tipos_productos')
            ->onDelete('restrict')
            ->onUpdate('cascade');
          $t->foreign('idunidad')
            ->references('idregistro')
            ->on('unidades')
            ->onDelete('restrict')
            ->onUpdate('cascade');
      });
      //TABLA DE IMPUESTOS_PRODUCTOS
      Schema::create('impuestos_productos', function (Blueprint $t) {
          $t->integer('idproducto')->unsigned();
          $t->integer('idimpuesto')->unsigned();
          $t->timestamp('creado')
            ->useCurrent()
            ->nullable();
          $t->timestamp('actualizado')
            ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
            ->nullable();
          $t->primary(['idproducto','idimpuesto']);
      });
      Schema::table('impuestos_productos', function (Blueprint $t) {
          $t->foreign('idproducto')
            ->references('idregistro')
            ->on('productos')
            ->onDelete('cascade')
            ->onUpdate('cascade');
          $t->foreign('idimpuesto')
            ->references('idregistro')
            ->on('impuestos')
            ->onDelete('restrict')
            ->onUpdate('cascade');
      });
      //TABLA DE INGRESOS_PRODUCTOS
      Schema::create('ingresos_productos', function (Blueprint $t) {
          $t->increments('idregistro');
          $t->integer('idproducto')->unsigned();
          $t->decimal('cantidad',10,4);
          $t->decimal('costo',10,4);
          $t->date('vencimiento')
            ->nullable();
          $t->timestamp('creado')
            ->useCurrent()
            ->nullable();
          $t->timestamp('actualizado')
            ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
            ->nullable();
      });
      Schema::table('ingresos_productos', function (Blueprint $t) {
          $t->foreign('idproducto')
            ->references('idregistro')
            ->on('productos')
            ->onDelete('cascade')
            ->onUpdate('cascade');
      });
      //TABLA DE DESCUENTOS
      Schema::create('descuentos', function (Blueprint $t) {
          $t->increments('idregistro');
          $t->string('nombre', 50);
          $t->mediumtext('descripcion')
            ->nullable();
          $t->date('inicio');
          $t->date('fin');
          $t->decimal('valor',10,4);
          $t->timestamp('creado')
            ->useCurrent()
            ->nullable();
          $t->timestamp('actualizado')
            ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
            ->nullable();
      });
      //TABLA DE DESCUENTOS_PRODUCTOS
      Schema::create('descuentos_productos', function (Blueprint $t) {
          $t->increments('iddescuento');
          $t->integer('idproducto')->unsigned();
          $t->timestamp('creado')
            ->useCurrent()
            ->nullable();
          $t->timestamp('actualizado')
            ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
            ->nullable();
      });
      Schema::table('descuentos_productos', function (Blueprint $t) {
          $t->foreign('idproducto')
            ->references('idregistro')
            ->on('productos')
            ->onDelete('cascade')
            ->onUpdate('cascade');
          $t->foreign('iddescuento')
            ->references('idregistro')
            ->on('descuentos')
            ->onDelete('restrict')
            ->onUpdate('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        $table->dropForeign('descuentos_productos_iddescuento_foreign');
        $table->dropForeign('descuentos_productos_idproducto_foreign');
        Schema::dropIfExists('descuentos_productos');
        Schema::dropIfExists('descuentos');
        $table->dropForeign('ingresos_productos_idproducto_foreign');
        Schema::dropIfExists('ingresos_productos');
        $table->dropForeign('impuestos_productos_idimpuesto_foreign');
        $table->dropForeign('impuestos_productos_idproducto_foreign');
        Schema::dropIfExists('impuestos_productos');
        $table->dropForeign('productos_idunidad_foreign');
        $table->dropForeign('productos_idtipo_foreign');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('impuestos');
        Schema::dropIfExists('tipos_productos');
        Schema::dropIfExists('unidades');
    }
}
