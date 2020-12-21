<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailDomain extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'domain',
        'client_id'
    ];

    /**
     * @return BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    /**
     * @param string $email
     * @return Client|null
     */
    public static function getClient(string $email)
    {
        $client_domain = EmailDomain::where('domain', $email)->first();
        if ($client_domain) {
            return $client_domain->client()->first();
        }
        list(,$user_domain) = explode('@', $email);
        $client_domain = EmailDomain::where('domain', $user_domain)->first();
        return $client_domain ? $client_domain->client()->first() : null;
    }
}
