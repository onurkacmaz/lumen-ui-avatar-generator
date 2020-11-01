<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApiController extends Controller
{
    use ResponseTrait;

    /**
     * @var AvatarGenerator
     */
    public $generator;

    /**
     * ApiController constructor.
     * @param AvatarGenerator $avatarGenerator
     */
    public function __construct(AvatarGenerator $avatarGenerator)
    {
        $this->generator = $avatarGenerator;
    }

    /**
     * @param Request $request
     * @return JsonResponse|BinaryFileResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required"
        ]);
        if ($validator->fails()) {
            return $this->responseError($validator->errors()->getMessages(), 400);
        }
        $image = $this->generator->setName($request->get("name"));
        $image = $image->upperCase();
        $image = $image->setBackgroundColor("#fafbfb");
        $image = $image->setTextColor("#000");
        $image = $image->upperCase();
        $image = $image->generate();
        return $this->responseSuccess($image, 200);
    }

}
