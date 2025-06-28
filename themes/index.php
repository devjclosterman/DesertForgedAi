<?php
// Silence is golden.
<section>
   
    <section class="status-strip">
<p>🟢 All Systems Operational | BTC: $111,200 | TAO RSI: 72 | Last Whale Alert: 6 mins ago</p>
</section>

<!-- <section class="news-ticker">
<marquee behavior="scroll" direction="left">
📢 BTC ETF inflows hit new high • TAO subnet builder in beta • BCH fork scheduled June 18 •
</marquee>
</section> -->

<section class="mini-tickers">
<div class="ticker">BTC: $111k ↑</div>
<div class="ticker">TAO: $397 ↓</div>
<div class="ticker">BCH: $509 ↑</div>
</section>

<!-- Under navbar -->
<section class="system-header">
<div class="system-ticker">
<div class="ticker-scroll">
BTC: $111,200 ↑ | TAO: $397 ↓ | BCH: $509 ↑ | RSI → TAO: 72 • BTC: 65 • BCH: 55 | Last Whale Alert: 6 mins ago
</div>
</div>
</section>


 <section>
  <canvas id="stars"></canvas>

    
  
    <div class="hero">
    <!-- Background balls -->
    <div class="ball back" style="top: 21%; left: 18%; width: 100px; height: 100px;"></div>
    <div class="ball back front" style="top: 70%; left: 13%; width: 280px; height: 280px;"></div>
    <!-- Foreground giant morph blob -->
    <div class="blob"></div>
    <!-- Foreground balls -->
    <div class="ball front" style="top: 25%; left: 65%; width: 60px; height: 60px;"></div>
    <div class="ball front" style="top: 55%; left: 60%; width: 90px; height: 90px;"></div>
    <!-- Text -->
    <div class="hero-text">
    <h1>This Could be Yours</h1>
    <p>Fast, Tactical. Ready for Deployment.</p>
    </div>
    </div>
 </section>

<div id="forge-chatbox">
<div class="forge-header">Forge AI</div>
<div id="forge-messages" class="forge-messages">
<div class="forge-bot">Hey there — I’m Forge AI. I learn more about our company and clients each day, so I can better serve both sides and save labor. Ask me anything about our services — or even general dev stuff.</div>
</div>
<input id="forge-input" type="text" placeholder="Ask me anything..." onkeydown="handleInput(event)">
</div>

<script>
  async function handleInput(event) {
    if (event.key === 'Enter') {
      const input = document.getElementById('forge-input');
      const userMessage = input.value.trim();
      if (!userMessage) return;

      addMessage('user', userMessage); // Show user message
      input.value = ''; // Clear input

      try {
        const response = await fetch('http://localhost:3000/chat', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ message: userMessage })
        });

        const data = await response.json();
        const aiReply = data.choices?.[0]?.message?.content || 'Sorry, something went wrong.';
        addMessage('bot', aiReply); // Show AI response
      } catch (err) {
        console.error(err);
        addMessage('bot', 'Error contacting AI server.');
      }
    }
  }

  function addMessage(sender, text) {
    const messages = document.getElementById('forge-messages');
    const msg = document.createElement('div');
    msg.className = sender === 'user' ? 'forge-user' : 'forge-bot';
    msg.textContent = text;
    messages.appendChild(msg);
    messages.scrollTop = messages.scrollHeight; // Auto-scroll
  }
</script>



  <section class="content">
   <div class="analytics">
    <h2>Live Analytics</h2>
    <p>Scroll through the command logs. Real-time crypto data loading...</p> 
   </div>
    
  <section class="charts-grid">
    <div class="chart-box">
    <h3>BTC Chart</h3>
    <canvas id="btcChart"></canvas>
    </div>
    <div class="chart-box">
    <h3>BCH Chart</h3>
    <canvas id="bchChart"></canvas>
    </div>
    <div class="chart-box">
    <h3>TAO Chart</h3>
    <canvas id="taoChart"></canvas>
    </div>
  </section>

<section class="live-price">
<h3>Live Price Tracker</h3>
<div class="price-values" id="priceTracker">
<!-- Populated dynamically -->
</div>
</section>
  </section>