<svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    
    <!-- Background Circle -->
    <circle cx="60" cy="60" r="55" fill="#4F46E5"/>

    <!-- Hub Nodes -->
    <circle cx="35" cy="35" r="8" fill="white"/>
    <circle cx="85" cy="35" r="8" fill="white"/>
    <circle cx="35" cy="85" r="8" fill="white"/>
    <circle cx="85" cy="85" r="8" fill="white"/>

    <!-- Center Node -->
    <circle cx="60" cy="60" r="12" fill="#C7D2FE"/>

    <!-- Connection Lines -->
    <line x1="35" y1="35" x2="60" y2="60"
          stroke="white"
          stroke-width="4"
          stroke-linecap="round"/>

    <line x1="85" y1="35" x2="60" y2="60"
          stroke="white"
          stroke-width="4"
          stroke-linecap="round"/>

    <line x1="35" y1="85" x2="60" y2="60"
          stroke="white"
          stroke-width="4"
          stroke-linecap="round"/>

    <line x1="85" y1="85" x2="60" y2="60"
          stroke="white"
          stroke-width="4"
          stroke-linecap="round"/>

    <!-- SmartHub Text -->
    <text x="60"
          y="108"
          text-anchor="middle"
          font-size="10"
          fill="white"
          font-family="Arial">
        SmartHub
    </text>

</svg>