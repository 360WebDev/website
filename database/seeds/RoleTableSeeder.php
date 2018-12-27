<?php

use App\Model\Role;
use App\Model\User;
use Illuminate\Database\Eloquent\Collection;
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
        $roles = new Collection();

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
            $role->users()->saveMany(factory(User::class, 5)->create());
        });
    }
}
