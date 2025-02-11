<template>
    <div class="chat" >
            <div id="messages" class="messages" >
                <div class="message"  v-for="message in messages.slice().reverse()"
                     :class="{'text-right': message.sent_by_user}"> {{message.text}}
                </div>
            </div>
        <input v-model="message_to_send"  class=" border rounded-lg"/>
        <button class="btn btn-outline-warning btn-sm ml-1.5" @click="send"> send</button>
    </div>

</template>
<script>

export default {
    props: ['to','user_id','db_messages'],

    data(){
        return {
            messages: this.db_messages,
            message_to_send: '',
            messages_container: '',
            new_message: {
                text: '',
                sent_by_user: true
            },

        }
    },
    mounted() {
        this.addSentByUser(this.user_id)
        Echo.private(`coje.${this.user_id}`)
            .listen("MessageSent", (response) => {
                this.messages.push(response.message);
            })
        this.messages_container = document.getElementById('messages')
        this.messages_container.scrollTop = this.messages_container.scrollHeight;
    },
    methods:{

        send(){
            axios.post('/chat/send/' + this.to, {message: this.message_to_send})
                .then(response => {
                    let message = {
                        text: response.data,
                        sent_by_user: true
                    }
                    this.messages.push(message)
                    this.$nextTick(function () {
                        this.messages_container.scrollTop = this.messages_container.scrollHeight;
                    })
                    this.message_to_send = ''
                })
                .catch(error => console.log(error))

        },

        addSentByUser(user_id){
            this.messages.forEach(function(message){
                if(message.from === user_id){
                    message.sent_by_user = true
                }
                else{
                    message.sent_by_user = false
                }
            });
        }
    }
}

</script>

<style>

.chat {

    position: relative;
    top: 1.5rem;
    margin: auto;
    max-width: 30rem;
    border: solid;
}
.messages {
    height: 350px;
    max-height: 350px;
    overflow: scroll;
    display: flex;
    height: 300px;
    flex-direction: column-reverse;
}
.message {
    margin-right: 3%;
}

</style>

