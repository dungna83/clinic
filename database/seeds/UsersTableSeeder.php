<?php

use Illuminate\Database\Seeder;
use App\Repositories\DoctorRepositoryEloquent;

class UsersTableSeeder extends Seeder
{
    /**
     * @var DoctorRepositoryEloquent
     */
    private $_user;

    function __construct(DoctorRepositoryEloquent $user)
    {
        $this->_user = $user;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->_user->create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123123'),
            'system_admin' => 1
        ]);
    }
}
