/**
 * EdConnect - Educational Social Media Platform
 * Main JavaScript File
 */

// Wait for DOM to be loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize popovers
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // Handle post like buttons
  const likeButtons = document.querySelectorAll('.btn-like');
  if (likeButtons) {
    likeButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        form.submit();
      });
    });
  }

  // Handle comment forms
  const commentForms = document.querySelectorAll('.comment-form');
  if (commentForms) {
    commentForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        const input = this.querySelector('input[name="content"]');
        if (!input.value.trim()) {
          e.preventDefault();
          return;
        }
      });
    });
  }

  // Mark notifications as read
  const notificationItems = document.querySelectorAll('.notification-item');
  if (notificationItems) {
    notificationItems.forEach(item => {
      item.addEventListener('click', function() {
        const notificationId = this.dataset.id;
        const url = `/notifications/${notificationId}/read`;
        
        fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        })
        .then(response => {
          if (response.ok) {
            this.classList.remove('unread');
            
            // Update badge count
            const badge = document.querySelector('.notification-badge');
            if (badge) {
              const count = parseInt(badge.textContent) - 1;
              if (count <= 0) {
                badge.style.display = 'none';
              } else {
                badge.textContent = count;
              }
            }
          }
        })
        .catch(error => console.error('Error marking notification as read:', error));
      });
    });
  }

  // Handle chat functionality
  const chatForm = document.querySelector('#chat-form');
  if (chatForm) {
    const chatMessages = document.querySelector('.chat-messages');
    
    // Scroll to bottom of chat messages
    if (chatMessages) {
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Handle chat form submission
    chatForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const messageInput = this.querySelector('input[name="message"]');
      const message = messageInput.value.trim();
      
      if (!message) return;
      
      const chatId = this.dataset.chatId;
      const url = `/chats/${chatId}/messages`;
      
      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ message: message })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Clear input
          messageInput.value = '';
          
          // Add new message to chat
          const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
          
          const messageElement = document.createElement('div');
          messageElement.className = 'chat-message outgoing';
          messageElement.innerHTML = `
            <div class="message-content">${message}</div>
            <div class="message-time">${timestamp}</div>
          `;
          
          chatMessages.appendChild(messageElement);
          
          // Scroll to bottom
          chatMessages.scrollTop = chatMessages.scrollHeight;
        }
      })
      .catch(error => console.error('Error sending message:', error));
    });
  }

  // Handle file inputs with custom filename display
  const fileInputs = document.querySelectorAll('.custom-file-input');
  if (fileInputs) {
    fileInputs.forEach(input => {
      input.addEventListener('change', function(e) {
        const fileName = this.files[0]?.name;
        const label = this.nextElementSibling;
        
        if (label && fileName) {
          label.textContent = fileName;
        }
      });
    });
  }

  // Handle attendance filter form
  const attendanceFilterForm = document.querySelector('#attendance-filter-form');
  if (attendanceFilterForm) {
    const semesterSelect = attendanceFilterForm.querySelector('select[name="semester"]');
    const yearSelect = attendanceFilterForm.querySelector('select[name="year"]');
    
    if (semesterSelect && yearSelect) {
      semesterSelect.addEventListener('change', function() {
        attendanceFilterForm.submit();
      });
      
      yearSelect.addEventListener('change', function() {
        attendanceFilterForm.submit();
      });
    }
  }

  // Handle results filter form
  const resultsFilterForm = document.querySelector('#results-filter-form');
  if (resultsFilterForm) {
    const semesterSelect = resultsFilterForm.querySelector('select[name="semester"]');
    const yearSelect = resultsFilterForm.querySelector('select[name="year"]');
    
    if (semesterSelect && yearSelect) {
      semesterSelect.addEventListener('change', function() {
        resultsFilterForm.submit();
      });
      
      yearSelect.addEventListener('change', function() {
        resultsFilterForm.submit();
      });
    }
  }

  // Handle admin filter forms
  const adminFilterForm = document.querySelector('.admin-filter-form');
  if (adminFilterForm) {
    const filterSelects = adminFilterForm.querySelectorAll('select');
    
    filterSelects.forEach(select => {
      select.addEventListener('change', function() {
        adminFilterForm.submit();
      });
    });
  }

  // Post character counter
  const postTextarea = document.querySelector('#post-content');
  if (postTextarea) {
    const charCounter = document.querySelector('#char-counter');
    const maxChars = 2000;
    
    postTextarea.addEventListener('input', function() {
      const remaining = maxChars - this.value.length;
      charCounter.textContent = remaining;
      
      if (remaining < 0) {
        charCounter.classList.add('text-danger');
        charCounter.classList.remove('text-muted');
      } else {
        charCounter.classList.remove('text-danger');
        charCounter.classList.add('text-muted');
      }
    });
  }

  // Confirmation dialogs
  const confirmForms = document.querySelectorAll('form[data-confirm]');
  if (confirmForms) {
    confirmForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        const message = this.dataset.confirm || 'Are you sure you want to perform this action?';
        
        if (!confirm(message)) {
          e.preventDefault();
        }
      });
    });
  }

  // Image preview for uploads
  const imageInputs = document.querySelectorAll('.image-upload');
  if (imageInputs) {
    imageInputs.forEach(input => {
      const preview = document.querySelector(input.dataset.preview);
      
      if (preview) {
        input.addEventListener('change', function() {
          const file = this.files[0];
          
          if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
              preview.src = e.target.result;
              preview.style.display = 'block';
            };
            
            reader.readAsDataURL(file);
          }
        });
      }
    });
  }

  // Real-time chat functionality
  // Note: This would be implemented with Laravel Echo and Pusher
  // For now, we'll simulate with polling
  
  const chatInterval = setInterval(function() {
    const chatContainer = document.querySelector('.chat-container');
    if (chatContainer) {
      const chatId = chatContainer.dataset.chatId;
      
      fetch(`/chats/${chatId}/messages/poll`, {
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.messages && data.messages.length > 0) {
          const chatMessages = document.querySelector('.chat-messages');
          
          data.messages.forEach(message => {
            // Check if message is already displayed
            const existingMessage = document.querySelector(`[data-message-id="${message.id}"]`);
            
            if (!existingMessage) {
              const messageElement = document.createElement('div');
              messageElement.className = `chat-message ${message.is_mine ? 'outgoing' : 'incoming'}`;
              messageElement.setAttribute('data-message-id', message.id);
              
              const time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
              
              messageElement.innerHTML = `
                <div class="message-content">${message.message}</div>
                <div class="message-time">${time}</div>
              `;
              
              chatMessages.appendChild(messageElement);
              chatMessages.scrollTop = chatMessages.scrollHeight;
            }
          });
        }
      })
      .catch(error => console.error('Error polling for messages:', error));
    } else {
      clearInterval(chatInterval);
    }
  }, 5000);
  
  // Poll for notifications
  const notificationInterval = setInterval(function() {
    if (document.querySelector('.notification-dropdown')) {
      fetch('/notifications/poll', {
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.count > 0) {
          const badge = document.querySelector('.notification-badge');
          
          if (badge) {
            badge.textContent = data.count;
            badge.style.display = 'block';
          }
          
          if (data.notifications && data.notifications.length > 0) {
            const container = document.querySelector('.notification-list');
            
            if (container) {
              data.notifications.forEach(notification => {
                const existingNotification = document.querySelector(`[data-id="${notification.id}"]`);
                
                if (!existingNotification) {
                  const time = new Date(notification.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                  
                  const element = document.createElement('a');
                  element.className = 'notification-item unread';
                  element.href = notification.link;
                  element.setAttribute('data-id', notification.id);
                  
                  element.innerHTML = `
                    <div class="notification-content">${notification.content}</div>
                    <div class="notification-time">${time}</div>
                  `;
                  
                  container.prepend(element);
                }
              });
            }
          }
        }
      })
      .catch(error => console.error('Error polling for notifications:', error));
    } else {
      clearInterval(notificationInterval);
    }
  }, 10000);
});
