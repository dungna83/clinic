<?php

class DoctorTest extends TestCase
{

    public function test_index_empty_list_doctor()
    {
        $mockDoctor = Mockery::mock(\App\Repositories\DoctorRepositoryEloquent::class);
        $mockDoctor
            ->shouldReceive('withTrashed')->once()->andReturnSelf()
            ->shouldReceive('orderBy')->once()->andReturnSelf()
            ->shouldReceive('all')->once()->andReturn(null);
        $this->installInstance($mockDoctor);
        $this->get(route('doctors.index'))->assertStatus(200);
    }

    public function test_index_show_all_doctor()
    {
        $mockDoctor = Mockery::mock(\App\Repositories\DoctorRepositoryEloquent::class);
        $mockDoctor
            ->shouldReceive('withTrashed')->once()->andReturnSelf()
            ->shouldReceive('orderBy')->once()->andReturnSelf()
            ->shouldReceive('all')->once()->andReturn(new \Illuminate\Database\Eloquent\Collection());
        $this->installInstance($mockDoctor);

        $this->get(route('doctors.index'))->assertStatus(200);
    }


    public function test_create_visit()
    {
        $this->get(route('doctors.create'))->assertStatus(200);
    }


    public function data_store_params()
    {
        return [
            [[
                'name' => '',
                'email' => '',
                'password' => '',
                're_password' => ''
            ]],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhm',
                'password' => '123',
                're_password' => '1444']
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '432123']
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhm',
                'password' => '123123',
                're_password' => '123123']
            ],
        ];
    }

    /**
     * @dataProvider data_store_params
     * @param $params
     */
    public function test_store_validate($params)
    {
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([true]);
        Validator::shouldReceive('errors')->once()->andReturn(new \Illuminate\Support\MessageBag());
        $this->post(route('doctors.store'), $params)->assertStatus(301);
    }


    public function data_store_success_params()
    {
        return [
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '123123'
            ]],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '123123'
            ]],
        ];
    }

    /**
     * @dataProvider data_store_success_params
     * @param $params
     */
    public function test_store_success($params)
    {
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([false]);

        $mockDoctor = Mockery::mock(\App\Repositories\DoctorRepositoryEloquent::class);
        $mockDoctor->shouldReceive('create')->once()->andReturn(new \App\Entities\Doctor());
        $this->installInstance($mockDoctor);

        $this->post(route('doctors.store'), $params)->assertStatus(302);
    }

    public function test_edit_id_not_found()
    {
        $mockDoctor = Mockery::mock(\App\Repositories\DoctorRepositoryEloquent::class);
        $mockDoctor->shouldReceive('find')->once()->andThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->installInstance($mockDoctor);

        $this->assertTrue(
            $this->seeExceptionThrown(
                \Illuminate\Database\Eloquent\ModelNotFoundException::class,
                function () {
                    $this->get(route('doctors.edit', ['id' => 1]));
                }
            )
        );

    }

    public function test_edit_success()
    {
        $mockDoctor = Mockery::mock(\App\Repositories\DoctorRepositoryEloquent::class);
        $mockDoctor->shouldReceive('find')->once()->andReturn(new \App\Entities\Doctor());
        $this->installInstance($mockDoctor);

        $this->get(route('doctors.edit', ['id' => 1]))->assertStatus(200);

    }


    public function data_update_params()
    {
        return [
            [[
                'name' => '',
                'email' => '',
                'password' => '',
                're_password' => '']
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhm',
                'password' => '123',
                're_password' => '1444']
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '432123']
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhm',
                'password' => '123123',
                're_password' => '123123'],
                'lang' => 'en'
            ],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '123123'
            ]]
        ];
    }

    /**
     * @dataProvider data_update_params
     * @param $params
     */
    public function test_update_validate($params)
    {
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([true]);
        Validator::shouldReceive('errors')->once()->andReturn(new \Illuminate\Support\MessageBag());
        $this->post(route('doctors.store'), $params)->assertStatus(301);
    }

    public function data_update_success_params()
    {
        return [
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '123123'
            ]],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com',
                'password' => '123123',
                're_password' => '123123'
            ]],
            [[
                'name' => 'Ha Anh Man',
                'email' => 'anhmantk@gmail.com'
            ]]
        ];
    }


    /**
     * @dataProvider data_update_success_params
     * @param $params
     */
    public function test_update_success($params)
    {
        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('fails')->once()->andReturnValues([false]);

        $mockDoctor = Mockery::mock(\App\Repositories\DoctorRepositoryEloquent::class);
        $mockDoctor->shouldReceive('update')->once()->andReturn(new \App\Entities\Doctor());
        $this->installInstance($mockDoctor);

        $this->put(route('doctors.update', ['id' => 1]), $params)->assertStatus(302);
    }

    public function test_destroy_block_doctor()
    {
        $mockDoctor = Mockery::mock(\App\Repositories\DoctorRepositoryEloquent::class);
        $mockDoctor->shouldReceive('withTrashed')->once()->andReturnSelf();
        $mockDoctor->shouldReceive('find')->once()->andReturn(new \App\Entities\Doctor());
        $mockDoctor->shouldReceive('delete')->once()->andReturn();
        $this->installInstance($mockDoctor);
        $this->delete(route('doctors.destroy', ['id' => 1]))->assertStatus(302);
    }

    public function test_destroy_restore_doctor()
    {
        $mockDoctor = Mockery::mock(\App\Repositories\DoctorRepositoryEloquent::class);
        $mockDoctor->shouldReceive('withTrashed')->once()->andReturnSelf();
        $doctor = factory(\App\Entities\Doctor::class)->times(1)->make();
        $doctor->deleted_at = \Carbon\Carbon::now() . '';

        $mockDoctor->shouldReceive('find')->once()->andReturn($doctor);
        $mockDoctor->shouldReceive('restore')->once()->andReturn();
        $this->installInstance($mockDoctor);
        $this->delete(route('doctors.destroy', ['id' => 1]))->assertStatus(302);
    }
}
