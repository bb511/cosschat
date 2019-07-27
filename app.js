import React from 'react'
import Chatkit from '@pusher/chatkit'
import MessageList from './components/MessageList'
import SendMessageForm from './components/SendMessageForm'
import RoomList from './components/RoomList'
import NewRoomForm from './components/NewRoomForm'

import { tokenUrl, instanceLocator } from './config'

class App extends React.Component {
    
    constructor() {
        super()
        this.state = {
	roomId: null,
              messages: [],
	joinableRooms: [],
	joinedRooms: []
        }
        this.sendMessage = this.sendMEssage.bind(this)
        this.subscribeToRoom = this.subscribeToRoom.bind(this)
        this.getRooms = this.getRooms.bind(this)

    } 
    
    componentDidMount() {
        const chatManager = new Chatkit.ChatManager({
            instanceLocator,
            userId: 'CERNID', /** swap out */
            tokenProvider: new Chatkit.TokenProvider({
                url: tokenUrl
        })
      })
        chatManager.connect()
        .then(currentUser => {
this.currentUser = currentUser
this.getRooms()
        })
       catch(err => console.log('error on connecting: ', err)
}

getRooms() {
	this.currentUser.getJoinableRooms()
		.then(joinableRooms => {
			This.setState({
				joinableRooms,
				joinedRooms: this.currentUser.rooms
			})
		})
		.catch(err => console.log('error on joinableRooms: ', err))
}

subscribeToRoom(roomId) {
	this.setState({ messages: [] })
	this.currentUser.subscribeToRoom({
		roomId: roomId,
		hooks: {
			onNewMessage: message => {
				this.setState({
					messages: […this.state.messages, message]
				})
			}
		}
	})
	.then(room => {
		this.setState({
	roomId: room.id
})
		this.getRooms()
	})
	.catch(err => console.log'error on subscribing to room: ', err ))
}

    
SendMessage(text) {
	this.currentUser.sendMessage({
		text,
roomId: this.state.roomId
	})
}

    render() {
        return (
            <div className="app">
                <RoomList 
		roomId={this.state.roomId}
subscribeToRoom={this.subscribeToRoom} 
rooms={[…this.state.joinableRooms, …this.state.joinedRooms]}/>
                <MessageList messages={this.state.messages} />
                <SendMessageForm sendMessage={this.sendMessage} />
                <NewRoomForm />
            </div>
        );
    }
}

export default App
