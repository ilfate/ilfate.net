<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

/**
 * List of Front Controllers
 */
return array(
  'all' => array(
    'generator' => array(
      1 => 'Game_MapGenerator'
    ),
    'bioms_list' => [
      'a' => 15,
      'b' => 8,
      'c' => 5,
    ],
    'planets' => [
      1 => [
        'x' => 10,
        'y' => 6,
      ],
    ],
    'default_biom' => 'a',

    'view' => [
      'screen_size' => 3,
      'cell_size'   => 64,
    ],
    'bioms' => [
      'a' => [ // common grass biom
        'cells_chances' => [
          'a1' => 40, // grass
          'a2' => 12, // dirt
          'a3' => 3,  //rocks
          'a4' => 3,  //hole
          'a5' => 2,  //pile,
          'a6' => 10,  //trees,
          'a7' => 2,  //puddle,
          'w1' => 3,  //water,
          's1' => 0,  //sand,
          't1' => 0.4, //resourse breaching
         ],
          'cell_multiply_coefficients' => [
            'a1' => 4,
            'a2' => 5,
            'a9' => 4,
            '10' => 4,
          ]
       ],
        'b' => [ // water biom
        'cells_chances' => [
          'a1' => 0, // grass
          'a2' => 0, // dirt
          'a3' => 0,  //rocks
          'a4' => 0,  //hole
          'a5' => 0,  //pile,
          'a6' => 0,  //trees,
          'a7' => 0,  //puddle,
          'w1' => 5, // water
          's1' => 0, //sand
          't1' => 0, //resourse breaching
         ],
          'cell_multiply_coefficients' => [
            'w1' => 3,
            's1' => 5,
            'a1' => 4,
          ]
       ],'c' => [ // rocks
        'cells_chances' => [
          'a1' => 25, // grass
          'a2' => 15, // dirt
          'a3' => 55,  //rocks
          'a4' => 10,  //hole
          'a5' => 2,  //pile,
          'a6' => 2,  //trees,
          'a7' => 2,  //puddle,
          'w1' => 0,  //water,
          's1' => 0,  //sand,
          't1' => 0.4, //resourse breaching
         ],
          'cell_multiply_coefficients' => [
            'a1' => 3,
            'a3' => 5,
            'a2' => 4,
          ]
       ],
     ],
  )
);


