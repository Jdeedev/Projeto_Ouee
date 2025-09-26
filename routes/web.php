<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;

Route::get('/', [ConversationController::class, 'index'])->name('home');
Route::get('/c/{id}', [ConversationController::class, 'show'])->name('conversation.show');
Route::get('/new', [ConversationController::class, 'new'])->name('new.conversation');
Route::post('/message/{conversation}', [MessageController::class, 'send'])->name('message.send');
