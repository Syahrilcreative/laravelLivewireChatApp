<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Users
                    </div>
                    <div class="card-body chat-box p-0">
                        <ul class="list-group list-group-flush">
                            @foreach ($users as $user)
                                @if ($user->id !== auth()->id())
                                    @php
                                        $not_seen =
                                            App\Models\Message::where('user_id', $user->id)
                                                ->where('receiver_id', auth()->id())
                                                ->where('is_seen', false)
                                                ->get() ?? null;
                                    @endphp
                                    <a wire:click="getUser({{ $user->id }})" class="text-dark link"
                                        style="text-decoration: none;">
                                        <li class="list-group-item">
                                            <img class="img-fluid avatar"
                                                src="https://cdn.pixabay.com/photo/2017/06/13/12/53/profile-2398782_1280.png">
                                            {{ $user->name }}
                                            @if ($user->is_online == true)
                                                <i class="bi bi-dot text-success"></i>
                                                <i class="fa fa-circle text-success online-icon">
                                            @endif
                                            </i>
                                            @if (filled($not_seen))
                                                <div class="badge badge-success rounded">{{ $not_seen->count() }}</div>
                                            @endif

                                        </li>
                                    </a>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            {{-- end col md-4 --}}
            @if ($openChat)
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    @if (isset($sender))
                                        {{ $sender->name }}
                                    @endif
                                </div>
                                <div class="col-6 text-right">
                                    <a wire:click="closeChat()" style="cursor: pointer">X</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body message-box" wire:poll="mountData">
                            @if (filled($allMessages))
                                @foreach ($allMessages as $message)
                                    <div
                                        class="single-message @if ($message->user_id == auth()->id()) sent @else receiver @endif">
                                        <p class="font-weight-bolder my-0">{{ $message->user->name }}</p>
                                        {{ $message->message }}
                                        <br>
                                        <small class="text-muted w-100">Sent <em>{{ $message->created_at }}</small>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="card-footer">
                            <form wire:submit.prevent="SendMessage">
                                <div class="row">
                                    <div class="col-md-8">
                                        <input wire:model="message"
                                            class="form-control input shadow-none w-100 d-inline-block"
                                            placeholder="Tulis Pesan">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary d-inline-block w-100"><i
                                                class="far fa-paper-plane"></i> Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
