<?php

namespace App\Http\Resources\Sessions;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SessionCollection extends ResourceCollection
{
    /**
     * @var string
     */
    public $ip;

    /**
     * @var string
     */
    public $user_agent;

    public function __construct($resource, $ip = null, $user_agent = null)
    {
        $this->ip = $ip;
        $this->user_agent = $user_agent;
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection->map(function ($session) {
                return [
                    'id' => $session->id,
                    'token' => $session->token,
                    'userAgent' => $this->detectBrowser($session),
                    'ip' => $session->ip,
                    'isActive' => $session->is_active,
                    'createdAt' => $session->created_at,
                    'isCurrent' => $this->isCurrentSession($session),
                ];
            }),
        ];
    }

    public function detectBrowser($session)
    {
        if (strpos($session->user_agent, 'Chrome')) {
            return 'Chrome';
        } else if (strpos($session->user_agent, 'Safari')) {
            return 'Safari';
        } else if (strpos($session->user_agent, 'Firefox')) {
            return 'Firefox';
        } else if (strpos($session->user_agent, '/Edge/i')) {
            return 'MS Edge';
        } else {
            return $session->user_agent;
        }
    }

    public function isCurrentSession($session)
    {
        if ($this->ip && $this->user_agent) {
            $rows = $this->collection->Where('ip', $this->ip)
                ->where('user_agent', $this->user_agent);
            $first = $rows->first();

            if (($this->ip === $session->ip) &&
                ($this->user_agent === $session->user_agent) &&
                ($session->id === $first->id)) {
                return true;
            }
        }

        return false;
    }
}
