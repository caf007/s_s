<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class llenar_tablas_inventario extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //DATOS TABLA UNIDADES
      DB::table('unidades')->insert(array(
        'nombre'=>'UNIDADES'
      ));
      DB::table('unidades')->insert(array(
        'nombre'=>'LIBRAS'
      ));
      DB::table('unidades')->insert(array(
        'nombre'=>'ONZAS'
      ));
      DB::table('unidades')->insert(array(
        'nombre'=>'KILOGRAMO'
      ));
      //DATOS TABLA IMPUESTOS
      DB::table('impuestos')->insert(array(
        'nombre'=>'15 %',
        'valor'=>0.15
      ));
      DB::table('impuestos')->insert(array(
        'nombre'=>'18 %',
        'valor'=>0.18
      ));
      //DATOS TABLA TIPOS PRODUCTOS
      DB::table('tipos_productos')->insert(array(
        'nombre'=>'REFRESCOS'
      ));
      DB::table('tipos_productos')->insert(array(
        'nombre'=>'CAFES'
      ));
      DB::table('tipos_productos')->insert(array(
        'nombre'=>'BELLEZA'
      ));
      DB::table('tipos_productos')->insert(array(
        'nombre'=>'BEBES'
      ));
      DB::table('tipos_productos')->insert(array(
        'nombre'=>'CUIDADO PERSONAL'
      ));
      //LLENAR TABLA PRODUCTOS
      $faker = Faker::create();
      for ($i=0; $i < 1000; $i++)
      {
        DB::table('productos')->insert(array(
          'codigo' => $faker->ean13,
          'nombre' => $faker->word,
          'descripcion' => $faker->text(200),
          'idtipo' => $faker->randomElement([1,2,3,4,5]),
          'idunidad' => $faker->randomElement([1,2,3,4]),
          'cantidad_minima' => $faker->randomDigit,
          'precio' => $faker->numberBetween(100, 5000),
          'perecedero' => $faker->randomElement([0,1]),
          'grabado' => $faker->randomElement([0,1])
        ));
      }
      $ps=DB::table('productos')->get();
      foreach($ps as $p)
      {
        if($p->grabado==1)
        {
          DB::table('impuestos_productos')->insert(array(
            'idproducto' => $p->idregistro,
            'idimpuesto' => $faker->randomElement([1,2])
          ));
        }
        DB::table('ingresos_productos')->insert(array(
          'idproducto' => $p->idregistro,
          'cantidad' => $faker->numberBetween(1, 500),
          'costo' => $p->precio * 0.70,
          'vencimiento' => $faker->dateTimeBetween('now', '2 years', null)
        ));

      }
    }
}
