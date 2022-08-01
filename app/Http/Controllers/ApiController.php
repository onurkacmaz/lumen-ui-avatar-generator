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

        if ($request->has("backgroundColor") && $request->filled("backgroundColor")) {
            $image->setBackgroundColor($request->get("backgroundColor"));
        }

        if ($request->has("fontSize") && $request->filled("fontSize")) {
            $image->setFontSize($request->get("fontSize"));
        }

        if (($request->has("width") && $request->filled("width")) || ($request->has("height") && $request->filled("height"))) {
            $image->setSize($request->get("width"), $request->get("height"));
        }

        if ($request->has("textColor") && $request->filled("textColor")) {
            $image->setTextColor($request->get("textColor"));
        }

        if ($request->has("upperCase") && $request->filled("upperCase") && (bool)$request->get("upperCase") === true) {
            $image->upperCase();
        }else {
            $image->lowerCase();
        }

        if ($request->has('rounded')) {
            $image->setRounded((int)$request->get('rounded'));
        }

        $image = $image->generate();

        if ($image["status"] !== 200) {
            return $this->responseError($image["errors"], $image["status"]);
        }

        return $this->responseSuccess($image["url"], $image["status"]);
    }

}
