<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactRequest;

class ContactController extends Controller
{
    use ApiResponse;

    public function __invoke(ContactRequest $request)
    {
        Comment::create($request->validated());
        return $this->apiResponse(1, "your Message added Successfully");

    }
}
