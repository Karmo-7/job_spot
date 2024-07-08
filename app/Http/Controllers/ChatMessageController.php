<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreChatRequest;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use illuminate\Http\jsonResponse;



class ChatMessageController extends Controller
{
     public function index(GetMessageRequest $request):JsonResponse{
        $data=$request->validated();
        $chatId=$data['chat_id'];
        $currentPage=$data['page'];
        $pageSize=$data['page_size'] ?? 15;
        $messages=ChatMessage::where('chat_id',$chatId)
        ->where('user')
        ->latest('created_at')
        ->simplePaginate(
            $pageSize,
            ['*'],
            'page',
            $currentPage
        );

        return $this->success($messages->getcollection());
     }

     public function store(StoreChatRequest $request){
        $data=$request->validated();
        $data['user_id']=auth()->user()->id;
        $chatMessage=ChatMessage::create($data);
        $chatMessage->load('user');
        return $this->success($chatMessage,'Message has been sent successfully');
        
     }

}
