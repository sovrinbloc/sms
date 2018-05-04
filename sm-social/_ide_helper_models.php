<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}


namespace  {
    exit("This file should not be included, only analyzed by your IDE");
}

namespace Illuminate\Support {

    /**
     * @method Fluent first()
     * @method Fluent after($column)
     * @method Fluent change()
     * @method Fluent nullable()
     * @method Fluent unsigned()
     * @method Fluent unique()
     * @method Fluent index()
     * @method Fluent primary()
     * @method Fluent spatialIndex()
     * @method Fluent default($value)
     * @method Fluent onUpdate($value)
     * @method Fluent onDelete($value)
     * @method Fluent references($value)
     * @method Fluent on($value)
     * @method Fluent charset($value)
     * @method Fluent collation($value)
     * @method Fluent comment($value)
     * @method Fluent autoIncrement()
     * @method Fluent storedAs($value)
     * @method Fluent useCurrent()
     * @method Fluent virtualAs($value)
     */
    class Fluent {

    }

}
