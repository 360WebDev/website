<?php

use App\Model\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = new \Illuminate\Database\Eloquent\Collection();

        $roles->add(Role::create(
            [
                'name'         => 'user',
                'slug'         => 'simple-user',
                'description'  => 'Just a simple user'
            ]
        ));
        $roles->add(Role::create([
            'name'         => 'moderator',
            'slug'         => 'moderator',
            'description'  => 'User can moderate comments and forum'
        ]));
        $roles->add(Role::create([
            'name'         => 'admin',
            'slug'         => 'admin',
            'description'  => 'User can moderate all and can write/edit post'
        ]));

        $roles->each(function($role) {
            $role->users()->saveMany(factory(\App\Model\User::class, 5)->create());
        });
    }
}
