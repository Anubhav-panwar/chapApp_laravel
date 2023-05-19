<!DOCTYPE html>
<html>
<head>
<title>ChatApp</title>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  </head>
<body>

<div class="container">
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card chat-app">
            <div id="plist" class="people-list">
                <div class="input-group">
                    <div class="input-group-prepend">
                    <ul class="list-unstyled chat-list mt-2 mb-0">                                 
                      <li class="clearfix">
                          <img src="{{  asset(Auth::user()->image)}}" style="width: 40px; height:40px;" alt="avatar">
                          <div class="about">
                         
                              <div class="name">{{ Auth::user()->name }}"</div>
                              
                          </div>
                      </li>                    
                  </ul>  
                  </div>
                  </div>
                  <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>

                



                <ul class="list-unstyled chat-list mt-2 mb-0">
                  
                   
                @foreach ($Person as $persons)  
                                 
                    <li class="clearfix" id="receiver_id" data="<?php echo $persons->id; ?>" onclick="GetData(<?php echo $persons->id; ?>)" >
                        
                        <img src="{{  asset($persons->image)}}" style="width: 40px; height:40px;" alt="avatar">
                        <div class="about">
                            <div class="name" >{{ $persons->name }}"</div>
                         
                        </div>
                    </li>
                    @endforeach 
                   
                </ul>
            </div>
            <div class="chat">
                <div class="chat-header clearfix">
                    <div class="row">
                        <div class="col-lg-6">
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                <img src="{{  asset('images/chatimage.jpg')}}" id="show_image" style="width: 40px; height:40px;"  alt="avatar">
                            </a>
                            <div class="chat-about">
                                
                                <h6 class="m-b-0" id="show">
                                      <small>Start Chat</small>
                                </h6>
                                <small></small>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6 hidden-sm text-right">
                            <a href="javascript:void(0);" class="btn btn-outline-secondary"><i class="fa fa-camera"></i></a>
                            <a href="javascript:void(0);" class="btn btn-outline-primary"><i class="fa fa-image"></i></a>
                            <a href="javascript:void(0);" class="btn btn-outline-info"><i class="fa fa-cogs"></i></a>
                             <a href="javascript:void(0);" class="btn btn-outline-warning"><i class="fa fa-question"></i></a>

                    </div> --}}
                    </div>
                   </div>

                <div class="chat-history">
                    <ul class="m-b-0" id="allmessages">

                        <li class="clearfix" style="text-align:center">
                           <img src="{{asset('images/chatimage.jpg')}}" height="320px" width="320px" alt="" srcset="">
                        </li>
                        {{-- <li class="clearfix">
                            <div class="message-data">
                                <span class="message-data-time">10:12 AM, Today</span>
                            </div>
                            <div class="message my-message">Are we meeting today?</div>                                    
                        </li>                               
                        <li class="clearfix">
                            <div class="message-data">
                                <span class="message-data-time">10:15 AM, Today</span>
                            </div>
                            <div class="message my-message">Project has been already finished and I have results to show you.</div>
                        </li> --}}
                    </ul>
                </div>
                <div class="chat-message clearfix">
                    <form id="data-form" method="POST">
                        @csrf 
                        <input type="hidden" id="show_id" name="reciver_id" value="">
                        <input type="hidden" id="sender_id" name="sender_id" value="<?php echo (Auth::user()->id); ?>">

                    <div class="input-group mb-0" id="textbox" style="display: none" >
                        
                        <input type="text" class="form-control" name="message" placeholder="Enter text here...">   
                        <div class="input-group-prepend">
                            
                            <button type="submit" class="btn btn-primary" onclick="Savechat()" > <i class="fa fa-send"></i></button>
                        </div>                                 
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
 

// 
function  GetData(id){
    
    
    $.ajaxSetup({
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')}
});
   
    $.ajax({
                url:"/showdetails",
                type:"get",
                responseType: 'blob',
                data:{ 'showid':id,                              
                },
                success:function(user){

                    var senderId = parseInt('{{ auth()->id() }}'); // Get the authenticated user's ID

                    var messages = user.Messages.concat(user.Receiver); // Merge sender and receiver messages
                    messages.sort(function(a, b) {
                        return new Date(a.created_at) - new Date(b.created_at); // Sort messages by timestamp
                    });

                    var listContainer = $('#allmessages'); // Get the container element
                    listContainer.empty(); // Clear previous list items

                    messages.forEach(function(message) {
                        var list = `<li class="clearfix">`;

                        if (message.sender_id === senderId) {
                            list += `<div class="message sender-message float-right " style="background-color:skyblue">`;
                        } else {
                            list += `<div class="message receiver-message float-left" style="background-color:lightgreen">`;
                        }

                        list += message.message + `</div>
                            </li>`;

                        listContainer.append(list); // Append new list items
                    });

               
                var userData=user.Users;  
                var base_url = window.location.origin ;    
                $('#textbox').removeAttr("style");                  
                $('#hidden_id').val(id);                   
                
                $("#show").text(userData.name);       
                $("#show_id").val(userData.id); 
                $('#show_image').attr('src', base_url +"/"+ userData.image);   
                
                
                
                                                                     
                }
              });
    }
    function Savechat(){
    $('#data-form').submit(function(e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "{{ route('save.chat') }}",
            data: $('#data-form').serialize(),
            success: function(data) {
                console.log(data);
            }
        });
    });


}



</script>





</body>
</html>