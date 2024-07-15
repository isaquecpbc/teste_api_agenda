<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        \App\Models\Schedule::create([
        	'user_id'           => 2,
        	'title'             => 'Casa Velha do Sr. Sugar',
        	'type'              => 'Viagem',
        	'description'       => 'Ao som da música "A Horse With No Name", vemos BoJack desaparecer do mapa e fugir dos problemas, dos amigos e do trabalho. Ele decide então revisitar a casa de seus avós. Por lá, conhece uma libélula assombrada pelo passado, mas disposta a ajudá-lo a reformar o lar de seus antepassados, que encontra-se em estado precário. Entre alguns flashbacks e muito trabalho, nosso protagonista muda de ideia e resolve demolir o passado. Literalmente.',
        	'status'            => true,
        	'start_at'          => '2024-07-15 08:00:00',
        	'conclusion_at'     => '2024-07-15 12:00:00',
        ]);
        // 2
        \App\Models\Schedule::create([
        	'user_id'           => 2,
        	'title'             => 'Farrear',
        	'type'              => 'Festa',
        	'description'       => 'BoJack convida Sarah Lynn, antiga colega de Horsin Around -- famoso seriado que alavancou a fama do cavalo nos anos 1990 --, para farrear como se não houvesse amanhã. O problema é que a ex-atriz e agora popstar estava em reabilitação e essa "aventura", enquanto satisfaz Horseman, apenas serve para acabar com a vida de Lynn. É uma das perdas mais marcantes na vida de BoJack, que irá carregar a culpa pelo resto da vida.',
        	'status'            => true,
        	'start_at'          => '2024-07-18 18:00:00',
        	'conclusion_at'     => '2024-07-18 23:45:00',
        ]);
        // 3
        \App\Models\Schedule::create([
        	'user_id'           => 3,
        	'title'             => 'Dallenor',
        	'type'              => 'Missão',
        	'description'       => 'Após sua inscrição na Ordem Jedi, Anakin Skywalker estava meditando com seu mestre Obi-Wan Kenobi quando foi informado sobre uma nova missão no planeta Dallenor para recuperar um holocron Jedi. Skywalker ficou insatisfeito por ficar com Yoda e os jovens, mas depois acompanhou Kenobi na missão. Durante a viagem, Anakin expressou insegurança sobre ser um Jedi. Em Dallenor, encontraram a arqueóloga Clatriffe e foram atacados por piratas liderados por Hudso Shaku. Anakin usou a Força para desarmar os piratas, salvando Kenobi. No retorno, Kenobi elogiou Anakin, que sentiu confiança renovada em ser um Jedi.',
        	'status'            => true,
        	'start_at'          => '2024-07-18 18:00:00',
        	'conclusion_at'     => '2024-07-18 23:45:00',
        ]);
    }
}
