import React from 'react'

const DUMMY_DATA =[
  {
    senderId: 'myakoo',
    text: 'Enjoy the chat (:'
    }
]

class MsgList extends React.Component {
  render() {
    return (
      <div className="msg-list">
        {DUMMY_DATA.map(message, index) => {
          return (
            <div key={index} className="msg">
              <div className="message-username">(message,senderId)</div>
              <div className="message-text">(message.text)</div>
            </div>
          )
      })}
     </div>
   )
  }
}
