<template>
  <div class="row justify-content-center">

    <div class="col-md-6">
      <div class="card-header">
        <div class="card-body p-0">
          <ul class="list-unstyled" style="height:300px;overflow:scroll" v-chat-scroll>
            <li class="p-2" v-for='message in messages'  >
              <strong>{{message.user.name}}</strong>
              {{message.massege}}<br>
              <span class="text-muted" v-if='user.id==message.user.id'>seen at {{time}}</span>
            </li>
          </ul>
        </div>

        <input type="text" name="message" class="form-control"autocomplete="off"
        @keydown="typing"
        v-model="newmessage"
        @keyup.enter="sendmessages"
        placeholder="write message here......">
        <span class="text-muted" v-if='activeUSer'>{{activeUSer.name}}  is typing</span>
      </div>
    </div>
    <div class="col-2">
      <div class="card card-default">
        <div class="card-header">active user</div>
        <div class="card-body">
          <ul>
            <li class="py-2" v-for='(user,index) in users' :key='index'>
              {{user.name}}
            </li>
            
          </ul>
          
          {{username.name}}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props:['user'],
    data()
    {
      return{
        messages:[],
        newmessage:'',
        users:[],
        username:'',
        activeUSer:false,
        seen:false,
        time:'',
      }

    },
    created(){
      this.getmessages();

      Echo.join('chat')
      .here(user=>{
        this.users=user;
        this.time = String(new Date().getHours()-12)+':'+String(new Date().getMinutes());
      })
      .leaving(user=>{
        this.users=this.users.filter(u=>u.id !=user.id);
        
      })
      .joining(user=>{
        this.users.push(user);
        
          this.seen=true;
       
        
      })
      .listen('sendMessageEvent',(e)=>{
        this.messages.push(e.message);
      })
      .listenForWhisper('typing',(user)=>{
        this.activeUSer=user;
        setTimeout(()=>{
          this.activeUSer=false;
        },2000);
      })
      .listen('onlineEvent',(e)=>{
        console.log(e);
        this.username=e.user;
        
      })
    },

    methods:{
      getmessages(){
        axios.get('messages').then(response=>{
          this.messages=response.data;
        });
      },
      sendmessages(){
        this.messages.push({
          user:this.user,
          massege:this.newmessage
        });
        axios({
          method: 'post',
          url: '/messages',
          data: {
            messege:this.newmessage,

          }
        }).then(function (response) {
        console.log(response.data);
        });
        this.newmessage="";

      },
    typing(){
      Echo.join('chat')
        .whisper('typing',this.user);
    }
    },
  }
</script>
