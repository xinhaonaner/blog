<?php

namespace Tests;

use Canvas\Models\User;

trait CreatesUser
{
    /**
     * The User model.
     *
     * @var User
     */
    private $user;

    /**
     * Create the User model test subject.
     *
     * @param array $data
     *
     * @return $this
     */
    public function createUser(array $data = [])
    {
        $this->user = factory(User::class)->create($data);

        return $this;
    }
}
