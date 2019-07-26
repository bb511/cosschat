import React from 'react'

class MessageList extends React.Component {
  render() {
    return (
      <div className="message-list">
        {<div key={index} className="msg">
              <div className="message-username">(message,senderId)</div>
              <div className="message-text">(message.text)</div>
            </div>
        }
     </div>
   )
  }
}
