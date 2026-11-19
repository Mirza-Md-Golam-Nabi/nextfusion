<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeamMemberResource;
use App\Models\TeamMember;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('team', [
            'members' => TeamMemberResource::collection(TeamMember::query()->active()->ordered()->get())->resolve(),
        ]);
    }
}
