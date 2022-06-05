<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CommentService;
use App\Services\ReviewService;
use App\Http\Controllers\Controller as Controller;
use Exception;

use App\Http\Requests\OwnerProduct\GetProductRequest;
use App\Http\Requests\Comment\AddCommentRequest;
use App\Http\Requests\Review\AddReviewRequest;

class ProductController extends Controller
{
    public function get_product_with_language(GetProductRequest $request)
    {
        try{
            $success = ProductService::get_product_with_language($request->product_id, $request->language_id, $request->currency_id);
            if($success == "product_not_found")
                return $this->sendError(__('messages.product_not_found'), 400);
            else
                return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function search_in_all_products(Request $request)
    {
        try{
            $success = ProductService::search_in_all_products($request);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function get_similar_owner_products_with_language(GetProductRequest $request)
    {
        try{
            $success = ProductService::get_similar_owner_products_with_language($request);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function get_highlighted_products(Request $request)
    {
        try{
            $success = ProductService::get_highlighted_products($request, $request->language_id, $request->currency_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_product_comments(GetProductRequest $request){
        try{
            $success = CommentService::get_product_comments($request, $request->product_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_product_reviews(GetProductRequest $request)
    {
        try{
            $success = ReviewService::get_product_reviews($request, $request->product_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_user_review_on_product(GetProductRequest $request)
    {
        try{
            $user = auth()->user();
            $success = ReviewService::get_user_review_on_product($user->id, $request->product_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function add_comment_to_product(AddCommentRequest $request)
    {
        try{
            $user = auth()->user();
            $success = CommentService::add_comment_to_product($request, $user->id);
            return $this->sendResponse(__('messages.added_comment_on_product'), $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function add_review_to_product(AddReviewRequest $request){
        try{
            $user = auth()->user();
            $user_did_review_on_product_flag = ReviewService::check_if_user_did_review_on_product($user->id, $request->product_id);
            if(!$user_did_review_on_product_flag){
                $success = ReviewService::add_review_to_product($request, $user->id);
                return $this->sendResponse(__('messages.added_review_on_product'), $success);
            }else
                return $this->sendError(__('messages.already_did_a_review'), 400);

        }catch(Exception $e){
            return $this->sendError($e->getMessage(), 500);
        }
    }





}
