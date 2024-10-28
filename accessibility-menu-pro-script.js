document.addEventListener('DOMContentLoaded', function() {
  const baseClassNames = tarray.base_class_names;
  const primaryMenuName = tarray.primary_menu_name;
  console.log("menu_options:", tarray.menu_options);

  baseClassNames.forEach(baseClassName => {
    excessNavRemover(baseClassName);
    applyGeneralStyles(baseClassName);

    console.log("baseClassName:", baseClassName);
    const submenuMode = tarray.menu_options[baseClassName].submenu_mode;
    const breakpointWidth = tarray.menu_options[baseClassName].breakpoint_width;
    const screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

    // console.log("breakpointWidth:", breakpointWidth);

    if (baseClassName === primaryMenuName) {
      if (submenuMode === "hover") {
        applyPrimaryMenuStyles(baseClassName, breakpointWidth);
        applyPrimaryHoverModeStyles(baseClassName, breakpointWidth);
        if (screenWidth < breakpointWidth) {
          enableDropdownArrowClicks(baseClassName);
        }
      } else {  // arrow click mode
        applyPrimaryMenuStyles(baseClassName, breakpointWidth);
        applyPrimaryArrowClicksStyle(baseClassName, breakpointWidth);
        enableDropdownArrowClicks(baseClassName);
      }
      createPrimaryMobileMenuPanel(baseClassName);
    } else {
      if (submenuMode === "always") {
        applySecondaryAlwaysModeStyles(baseClassName, breakpointWidth);
      } else {  // arrow click mode
        applySecondaryArrowModeStyles(baseClassName, breakpointWidth);
        enableDropdownArrowClicks(baseClassName);
      }
    }
  });  
});

function applyPrimaryMenuStyles(baseClassName, breakpointWidth) {
  const submenuElementWidth = tarray.menu_options[baseClassName].submenu_element_width;

  // barry to improve styling here
  var style = document.createElement("style");
  style.innerHTML = `
    nav#${baseClassName}_accessibility_menu_pro_nav div.${baseClassName}_accessibility_menu_pro_wrapper_inner ul.${baseClassName}_accessibility_menu_pro_sub_menu.is-visible {
      position: relative;
      display: block;
      opacity: 1;
      pointer-events: auto;
      height: auto;
    }

    @media(min-width: ${breakpointWidth}px) {
      #${baseClassName}_accessibility_menu_pro_nav_wrapper #${baseClassName}_accessibility_menu_pro_nav {
        width: auto;
      }

      /* Display submenu on keyboard/mobile */
      nav#${baseClassName}_accessibility_menu_pro_nav div.${baseClassName}_accessibility_menu_pro_wrapper_inner ul.${baseClassName}_accessibility_menu_pro_sub_menu.is-visible {
        display: block;
        opacity: 1;
        height: auto;
        position: absolute;
        overflow: visible;
      }

      #${baseClassName}_accessibility_menu_pro_ul {
        flex-direction: row;
      }

      #${baseClassName}_accessibility_menu_pro_ul li {
        width: auto;
      }
    
      #${baseClassName}_accessibility_menu_pro_ul .${baseClassName}_accessibility_menu_pro_has_sub_menu > a {
        padding-right: 50px;
      }
    
      .${baseClassName}_accessibility_menu_pro_dd_button {
        width: 45px;
      }
    
      #${baseClassName}_accessibility_menu_pro_ul > li ul button {
        transform: rotate(-90deg);
      }
    
      #${baseClassName}_accessibility_menu_pro_ul > li ul {
        margin: 0;
        position: absolute;
        left: 0;
        min-width: ${submenuElementWidth}px;
      }
    
      #${baseClassName}_accessibility_menu_pro_ul >li > ul > li ul {
        left: ${Number(submenuElementWidth) - 1}px;
        top: 0;
      }

      #${baseClassName}_accessibility_menu_pro_ul > li:hover > .${baseClassName}_accessibility_menu_pro_sub_menu {
        left: -1px;
      }
    }
  `;
  document.head.appendChild(style);
}

function applyPrimaryHoverModeStyles(baseClassName, breakpointWidth) {
  var style = document.createElement("style");
  style.innerHTML = `
  @media(min-width: ${breakpointWidth}px) {
    /* Desktop: display submenu on hover */
      nav#${baseClassName}_accessibility_menu_pro_nav div.${baseClassName}_accessibility_menu_pro_wrapper_inner .${baseClassName}_accessibility_menu_pro_list_item:hover > ul.${baseClassName}_accessibility_menu_pro_sub_menu {
        display: block;
        opacity: 1;
        height: auto;
        position: absolute;
        overflow: visible;
      }
    }
  `;
  document.head.appendChild(style);
}

function applyPrimaryArrowClicksStyle(baseClassName, breakpointWidth) {
  var style = document.createElement("style");
  console.log("applyPrimaryArrowClicksStyle...")
  style.innerHTML = `
    nav#${baseClassName}_accessibility_menu_pro_nav div.${baseClassName}_accessibility_menu_pro_wrapper_inner ul.${baseClassName}_accessibility_menu_pro_sub_menu ul {
      left: 234px;
    } 
  `;
  document.head.appendChild(style);
}

function applySecondaryAlwaysModeStyles(baseClassName, breakpointWidth) {
  // todo: add support for widescreen footer menu

  var style = document.createElement("style");
  style.innerHTML = `
    nav#${baseClassName}_accessibility_menu_pro_nav div.${baseClassName}_accessibility_menu_pro_wrapper_inner ul.${baseClassName}_accessibility_menu_pro_sub_menu {
      display: block;
      padding-left: 20px;
    }

    nav#${baseClassName}_accessibility_menu_pro_nav div.${baseClassName}_accessibility_menu_pro_wrapper_inner ul.${baseClassName}_accessibility_menu_pro_sub_menu ul {
      padding-left: 20px;
    }

    .${baseClassName}_accessibility_menu_pro_dd_button {
      display: none;
    }   

    @media(min-width: ${breakpointWidth}px) {
      #${baseClassName}_accessibility_menu_pro_ul {
        flex-direction: column;
      }

      #${baseClassName}_accessibility_menu_pro_ul li {
        width: auto;
      }
    }
  `;
  document.head.appendChild(style);
}

function applySecondaryArrowModeStyles(baseClassName, breakpointWidth) {
  var style = document.createElement("style");
  style.innerHTML = `
    nav#${baseClassName}_accessibility_menu_pro_nav div.${baseClassName}_accessibility_menu_pro_wrapper_inner ul.${baseClassName}_accessibility_menu_pro_sub_menu.is-visible {
      display: block;
    }

    nav#${baseClassName}_accessibility_menu_pro_nav div.${baseClassName}_accessibility_menu_pro_wrapper_inner ul.${baseClassName}_accessibility_menu_pro_sub_menu {
      padding-left: 20px;
    }

    nav#${baseClassName}_accessibility_menu_pro_nav div.${baseClassName}_accessibility_menu_pro_wrapper_inner ul.${baseClassName}_accessibility_menu_pro_sub_menu ul {
      padding-left: 20px;
    } 
  `;
  document.head.appendChild(style);
}

function enableDropdownArrowClicks(baseClassName) {
  document.querySelectorAll('.' + baseClassName + '_accessibility_menu_pro_dd_button').forEach(ddb => {
    ddb.onclick = function(event) {
      event.preventDefault();
      const parentLi = this.parentNode;
      const submenu = parentLi.querySelector('.' + baseClassName + '_accessibility_menu_pro_sub_menu');
      submenu.classList.toggle('is-visible');
      const ariaExpanded = this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true';
      this.setAttribute('aria-expanded', ariaExpanded);
    }
  });
}

function excessNavRemover(baseClassName) {
  const targetNav = document.getElementById(`${baseClassName}_accessibility_menu_pro_nav`);
  if (targetNav && targetNav.closest('nav')) {

    const parentDiv = targetNav.parentElement.closest('div'); // new wrapper div
    const parentNav = parentDiv.parentElement.closest('nav');

    if (parentNav !== targetNav) {
      const previousSiblingNav = parentNav.previousElementSibling;
      const nextSiblingNav = parentNav.nextElementSibling;
      if (previousSiblingNav && previousSiblingNav.tagName === "NAV") {
        previousSiblingNav.remove();
      }
      if (nextSiblingNav && nextSiblingNav.tagName === "NAV") {
        nextSiblingNav.remove();
      }
      parentNav.replaceWith(parentDiv);
    }
  }
}

function checkDefaultFont(font) {
  let defaultFonts = {
    "Arial": ["regular", "italic", "bold", "bold italic"],
    "Georgia": ["regular", "italic", "bold", "bold italic"],
    "Helvetica": ["regular", "italic", "bold", "bold italic"],
    "Verdana": ["regular", "italic", "bold", "bold italic"],
    "Times New Roman": ["regular", "italic", "bold", "bold italic"]
  }
  return defaultFonts.hasOwnProperty(font);
}

function applyGeneralStyles(baseClassName) {
  const justifyContent = tarray.menu_options[baseClassName].justify_content;
  const parentLinkPaddingTop = tarray.menu_options[baseClassName].parent_link_padding_top;
  const parentLinkPaddingRight = tarray.menu_options[baseClassName].parent_link_padding_right;
  const parentLinkPaddingBottom = tarray.menu_options[baseClassName].parent_link_padding_bottom;
  const parentLinkPaddingLeft = tarray.menu_options[baseClassName].parent_link_padding_left;

  const submenuLinkPaddingTop = tarray.menu_options[baseClassName].submenu_link_padding_top;
  const submenuLinkPaddingRight = tarray.menu_options[baseClassName].submenu_link_padding_right;
  const submenuLinkPaddingBottom = tarray.menu_options[baseClassName].submenu_link_padding_bottom;
  const submenuLinkPaddingLeft = tarray.menu_options[baseClassName].submenu_link_padding_left;

  const parentTextColor = tarray.menu_options[baseClassName].parent_text_color;
  const parentHoverTextColor = tarray.menu_options[baseClassName].parent_hover_text_color;
  const parentFontSize = tarray.menu_options[baseClassName].parent_font_size;
  const parentFont = tarray.menu_options[baseClassName].parent_font;
  const parentFontStyle = tarray.menu_options[baseClassName].parent_font_style;
  const submenuTextColor = tarray.menu_options[baseClassName].submenu_text_color;
  const submenuHoverTextColor = tarray.menu_options[baseClassName].submenu_hover_text_color;
  const submenuFont = tarray.menu_options[baseClassName].submenu_font;
  const submenuFontStyle = tarray.menu_options[baseClassName].submenu_font_style;
  const submenuFontSize = tarray.menu_options[baseClassName].submenu_font_size;

  const parentTextTransform = tarray.menu_options[baseClassName].parent_text_transform;
  const submenuTextTransform = tarray.menu_options[baseClassName].submenu_text_transform;

  const parentBackgroundColor = tarray.menu_options[baseClassName].parent_background_color;
  const parentHoverBackgroundColor = tarray.menu_options[baseClassName].parent_hover_background_color;
  const submenuBackgroundColor = tarray.menu_options[baseClassName].submenu_background_color;
  const submenuHoverBackgroundColor = tarray.menu_options[baseClassName].submenu_hover_background_color;

  const mobileMenuOverlayBackgroundColor = tarray.menu_options[baseClassName].mobile_menu_overlay_background_color; // move to mobile function?

  var parentFontFace = '';
  var parentFontStyling = '';
  const parentIsDefaultFont = checkDefaultFont(parentFont);
  if (parentIsDefaultFont) {
    parentFontStyling = getDefaultFontStyling(parentFontStyle);
  } else {
    const splitParentFontStyle = parentFontStyle.split("-");
    const parentVersionString = splitParentFontStyle[0];
    const parentStylingString = splitParentFontStyle[2];
    const parentFontDir = `${parentFont}-${parentVersionString}-latin`;
    const parentFontPath = `${tarray.base_url}/wp-content/plugins/accessibility-menu-pro/fonts/${parentFontDir}/${parentFontDir}-${parentStylingString}.woff2`;

    parentFontFace = `
      @font-face {
        font-family: "${parentFont}";
        font-display: swap;
        src: url("${parentFontPath}") format("woff2");
      }
    `;
  }

  var submenuFontFace = '';
  var submenuFontStyling = '';
  const submenuIsDefaultFont = checkDefaultFont(submenuFont);
  if (submenuIsDefaultFont) {
    submenuFontStyling = getDefaultFontStyling(submenuFontStyle);
  } else {
    const splitSubmenuFontStyle = submenuFontStyle.split("-");
    const submenuVersionString = splitSubmenuFontStyle[0];
    const submenuStylingString = splitSubmenuFontStyle[2];

    const submenuItalics = submenuStylingString.indexOf("italic") > 0 ? "italic" : "normal";

    const submenuFontDir = `${submenuFont}-${submenuVersionString}-latin`;
    const submenuFontPath = `${tarray.base_url}/wp-content/plugins/accessibility-menu-pro/fonts/${submenuFontDir}/${submenuFontDir}-${submenuStylingString}.woff2`;

    submenuFontFace = `
      @font-face {
        font-family: "${submenuFont}";
        font-display: swap;
        src: url("${submenuFontPath}") format("woff2");
        font-style: ${submenuItalics};
      }
    `;
    submenuFontStyling = `
      font-style: ${submenuItalics};
    `;
  }

  // these could be improved...
  const parentDropdownButtonHeight = Number(parentLinkPaddingTop) * 3;
  const parentDropdownButtonPaddingTop = Number(parentLinkPaddingTop * 0.3) + (Number(parentFontSize) * 0.2);
  const submenuDropdownButtonHeight = Number(submenuLinkPaddingTop) * 2;
  const submenuDropdownButtonMarginTop = Number(submenuLinkPaddingTop * 0.9) + (Number(submenuLinkPaddingTop) * 0.15);

  var style = document.createElement("style");
  style.innerHTML = `

    .${baseClassName}_accessibility_menu_pro_dd_button {
      background-color: hsla(0, 100%, 0%, 0.0);
      width: 55px;
      /*  height: 51px; todo barry */
      height: ${parentDropdownButtonHeight}px;
      padding-top: ${parentDropdownButtonPaddingTop}px;
      display: flex;
      justify-content: center;
      align-items: center;
      border: 1px solid transparent; /* todo: minor issue with submenu top position */
      color: ${parentTextColor};
      text-decoration: none;
      font-size: ${parentFontSize}px;
      cursor: pointer;
      position: absolute;
      top: 0;
      right: 0;
      left: auto;
      bottom: 0;
      border-radius: 0;
    }

    #${baseClassName}_accessibility_menu_pro_ul > li:hover > a,
    #${baseClassName}_accessibility_menu_pro_ul > li:hover > button {
      color: ${parentHoverTextColor};
    }

    ul ul .${baseClassName}_accessibility_menu_pro_dd_button {
      height: ${submenuDropdownButtonHeight * 0.5}px;
      margin-top: ${submenuDropdownButtonMarginTop}px;
    }

    .${baseClassName}_accessibility_menu_pro_dd_button:hover {
      background-color: transparent !important;
    }

    #${baseClassName}_accessibility_menu_pro_nav_wrapper {
      margin: 0;      /* please keep */
      padding: 0;     /* please keep */
      width: 100%;    /* please keep */
      justify-content: ${justifyContent} !important;
    }

    .${baseClassName}_accessibility_menu_pro_nav_wrapper_visible {
      display: flex !important;
      align-items: center;
      position: fixed;
      overflow: scroll;
      overflow-x: hidden;
      top: 0; right: 0; bottom: 65px; left: 0;
      width: 100%;
      width: 100vw;
      background: white;
      z-index: 91;
      padding: 20px 10% !important;
      background-color: ${mobileMenuOverlayBackgroundColor};
    }

    .${baseClassName}_accessibility_menu_pro_nav_wrapper_hidden_on_mobile {
      display: none !important;
    }
    
    #${baseClassName}_accessibility_menu_pro_nav_wrapper #${baseClassName}_accessibility_menu_pro_nav {
      margin: 0;    /* please keep */
      padding: 0;   /* please keep */
      /* border-left: 1px solid black;   can keep this line for further testing - or fine to remove */
      /* border-right: 1px solid black;  can keep this line for further testing - or fine to remove */
    }
    
    #${baseClassName}_accessibility_menu_pro_nav {
      background-color: transparent;
      position: relative;
      width: 100%;
      display: flex !important;
      justify-content: ${justifyContent} !important;
      align-content: center;
      overflow: visible;
      z-index: 90;
    }
    
    .${baseClassName}_accessibility_menu_pro_wrapper_inner {
      position: relative;
      width: 100%;
      /*max-width: 1600px;*/
      background-color: transparent;
    }

    ${parentFontFace}
    
    #${baseClassName}_accessibility_menu_pro_ul,
    #${baseClassName}_accessibility_menu_pro_ul li {
      position: relative;
      width: 100%;
      list-style-type: none !important;
      padding: 0;
      margin: 0;
      font-family: "${parentFont}", "Arial";
      ${parentFontStyling}
    }
    
    #${baseClassName}_accessibility_menu_pro_ul {
      display: flex;
      flex-direction: column;
      flex-wrap: nowrap;
      justify-content: center;
      background-color: ${parentBackgroundColor};
    }
    
    #${baseClassName}_accessibility_menu_pro_ul a {  /* every anchor in parent & submenu */
      position: relative;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: ${parentLinkPaddingTop}px ${parentLinkPaddingRight}px ${parentLinkPaddingBottom}px ${parentLinkPaddingLeft}px;
      color: ${parentTextColor};
      text-decoration: none;
      border: 1px solid transparent;
      font-size: ${parentFontSize}px;
      border: 1px solid transparent;
      line-height: 1;
    }
    
    #${baseClassName}_accessibility_menu_pro_ul > li > a { /* only parent anchors */
      /* font-weight: bold; */
      text-transform: ${parentTextTransform};
    }

    ${submenuFontFace}
    
    #${baseClassName}_accessibility_menu_pro_ul ul li a { /* only submenu anchors */
      font-size: ${submenuFontSize}px;
      font-family: "${submenuFont}";
      color: ${submenuTextColor};
      ${submenuFontStyling};
      padding: ${submenuLinkPaddingTop}px ${submenuLinkPaddingRight}px ${submenuLinkPaddingBottom}px ${submenuLinkPaddingLeft}px;
      text-transform: ${submenuTextTransform};
    }

    #${baseClassName}_accessibility_menu_pro_ul ul li a:hover { /* only submenu anchors */
      color: ${submenuHoverTextColor};
    }
    
    #${baseClassName}_accessibility_menu_pro_ul ul .${baseClassName}_accessibility_menu_pro_dd_button {
      color: ${submenuTextColor};
      margin-right: 2px;
      /* height : todo */
    }

    #${baseClassName}_accessibility_menu_pro_ul ul .${baseClassName}_accessibility_menu_pro_dd_button:hover {
      color: ${submenuHoverTextColor};
    }

    #${baseClassName}_accessibility_menu_pro_ul li:hover {
      background-color: ${parentHoverBackgroundColor};
    }
    
    #${baseClassName}_accessibility_menu_pro_ul a:active,
    #${baseClassName}_accessibility_menu_pro_ul a:focus,
    #${baseClassName}_accessibility_menu_pro_ul .${baseClassName}_accessibility_menu_pro_dd_button:focus {
      border: 1px dashed gold;
    }
    
    .${baseClassName}_accessibility_menu_pro_list_item {
      border: 1px dashed transparent;
    }
    
    .${baseClassName}_accessibility_menu_pro_list_item:focus {
      border: 1px dashed gold;
    }
    
    .${baseClassName}_accessibility_menu_pro_list_item {
      position: relative;
    }
    
    nav#${baseClassName}_accessibility_menu_pro_nav div.${baseClassName}_accessibility_menu_pro_wrapper_inner ul.${baseClassName}_accessibility_menu_pro_sub_menu {
      display: none;
    }
    
    #${baseClassName}_accessibility_menu_pro_nav .${baseClassName}_accessibility_menu_pro_wrapper_inner .${baseClassName}_accessibility_menu_pro_sub_menu {
      background-color: ${submenuBackgroundColor};
    }

    #${baseClassName}_accessibility_menu_pro_nav .${baseClassName}_accessibility_menu_pro_wrapper_inner .${baseClassName}_accessibility_menu_pro_sub_menu li:hover {
      background-color: ${submenuHoverBackgroundColor};
    }
  `;
  document.head.appendChild(style);
}

function getDefaultFontStyling(fontStyle) {
  switch (fontStyle) {
    case 'regular':
      return `
        font-weight: normal;
        font-style: normal;
      `;
    case 'bold':
      return `
        font-weight: bold;
        font-style: normal;
      `;
    case 'italic':
      return `
        font-weight: normal;
        font-style: italic;
      `;
    case 'bold italic':
      return `
        font-weight: bold;
        font-style: italic;
      `;
    default:
      return `
        font-weight: normal;
        font-style: normal;
      `;
  }
}

function togglePrimaryMenuMobile(baseClassName) {
  var primaryMenuWrapper = document.getElementById(`${baseClassName}_accessibility_menu_pro_nav_wrapper`);

  if (primaryMenuWrapper.classList.length === 0) {
    primaryMenuWrapper.classList.add(`${baseClassName}_accessibility_menu_pro_nav_wrapper_visible`);
  } else if (primaryMenuWrapper.classList.contains(`${baseClassName}_accessibility_menu_pro_nav_wrapper_visible`)) {
    primaryMenuWrapper.classList.replace(`${baseClassName}_accessibility_menu_pro_nav_wrapper_visible`, `${baseClassName}_accessibility_menu_pro_nav_wrapper_hidden_on_mobile`);
  } else {
    primaryMenuWrapper.classList.replace(`${baseClassName}_accessibility_menu_pro_nav_wrapper_hidden_on_mobile`, `${baseClassName}_accessibility_menu_pro_nav_wrapper_visible`);
  }

  const elevatorSvg = document.getElementById(`${baseClassName}_elevator_svg`);
  elevatorSvg.classList.toggle("elevbtn-active");

  toggleBodyOverflow();
}

function toggleBodyOverflow() {
  const bodyElement = document.body;
  if (bodyElement.classList.contains("mobile_body_mobile_active")) {
    bodyElement.classList.replace("mobile_body_mobile_active", "mobile_body_default");
  } else if (bodyElement.classList.contains("mobile_body_default")) {
    bodyElement.classList.replace("mobile_body_default", "mobile_body_mobile_active");
  } else {
    bodyElement.classList.add("mobile_body_default");
  }
}

function createPrimaryMobileMenuPanel(baseClassName) {
  const breakpointWidth = tarray.menu_options[baseClassName].breakpoint_width;
  const mobileMenuButtonBackgroundColor = tarray.menu_options[baseClassName].mobile_menu_button_background_color;
  const mobileMenuButtonFont = tarray.menu_options[baseClassName].mobile_menu_button_font;
  const mobileMenuButtonFontStyle = tarray.menu_options[baseClassName].mobile_menu_button_font_style;
  const mobileMenuButtonFontSize = tarray.menu_options[baseClassName].mobile_menu_button_font_size;
  const mobileMenuButtonTextColor = tarray.menu_options[baseClassName].mobile_menu_button_text_color;

  var mobileMenuButtonFontFace = '';
  var mobileMenuButtonFontStyling = '';
  const mobileMenuButtonIsDefaultFont = checkDefaultFont(mobileMenuButtonFont);
  if (mobileMenuButtonIsDefaultFont) {
    mobileMenuButtonFontStyling = getDefaultFontStyling(mobileMenuButtonFontStyle);
  } else {
    const splitMobileMenuButtonFontStyle = mobileMenuButtonFontStyle.split("-");
    const mobileMenuButtonVersionString = splitMobileMenuButtonFontStyle[0];
    const mobileMenuButtonStylingString = splitMobileMenuButtonFontStyle[2];
    const mobileMenuButtonFontItalics = mobileMenuButtonStylingString.indexOf("italic") > 0 ? "italic" : "normal";
    const mobileMenuButtonFontDir = `${mobileMenuButtonFont}-${mobileMenuButtonVersionString}-latin`;
    const mobileMenuButtonFontPath = `${tarray.base_url}/wp-content/plugins/accessibility-menu-pro/fonts/${mobileMenuButtonFontDir}/${mobileMenuButtonFontDir}-${mobileMenuButtonStylingString}.woff2`;

    mobileMenuButtonFontFace = `
      @font-face {
        font-family: "${mobileMenuButtonFont}";
        font-display: swap;
        src: url("${mobileMenuButtonFontPath}") format("woff2");
      }
    `;
    mobileMenuButtonFontStyling = `
      font-style: ${mobileMenuButtonFontItalics};
    `
  }

  console.log("mobileMenuButtonTextColor:", mobileMenuButtonTextColor);

  var html = document.createElement("div");
  html.innerHTML = `
    <div id="${baseClassName}_mobile" class="${baseClassName}_mobile_button_container">
      <button id="${baseClassName}_mobile_button" aria-label="Menu" onclick="togglePrimaryMenuMobile('${baseClassName}')">
        <svg id="${baseClassName}_elevator_svg" class="elevbtn elevbtnRotate elevbtn8" viewBox="0 0 100 100" width="40">
          <path class="elevbtnLine top" d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20" />
          <path class="elevbtnLine middle" d="m 30,50 h 40" />
          <path class="elevbtnLine bottom"  d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20" />
        </svg>
        <span class="${baseClassName}_mobile_button_label">Menu</span>
      </button>
    </div>
  `;
  document.body.appendChild(html);

  var style = document.createElement("style");
  style.innerHTML = `
    .mobile_body_default {
      overflow: auto;
      overflow-x: hidden !important;
      -webkit-overflow-scrolling: touch;
    }

    .mobile_body_menu_active {
      overflow: hidden;
    }

    .elevbtn {
      cursor: pointer;
      -webkit-tap-highlight-color: transparent;
      transition: transform 400ms;
      -moz-user-select: none;
      -webkit-user-select: none;
      -ms-user-select: none;
      user-select: none;
      fill: ${mobileMenuButtonTextColor};
    }
    .elevbtnRotate.elevbtn-active {
      transform: rotate(45deg)
    }
    .elevbtnRotate180.elevbtn-active {
      transform: rotate(180deg);
    }

    .elevbtnLine {
      fill: none;
      transition: stroke-dasharray 400ms, stroke-dashoffset 400ms;
      stroke: ${mobileMenuButtonTextColor};
      stroke-width: 4;
      stroke-linecap: round;
    }

    .elevbtn8 .top {
      stroke-dasharray: 40 160;
    }
    .elevbtn8 .middle {
      stroke-dasharray: 40 142;
      transform-origin: 50%;
      transition: transform 400ms;
    }
    .elevbtn8 .bottom {
      stroke-dasharray: 40 85;
      transform-origin: 50%;
      transition: transform 400ms, stroke-dashoffset 400ms;
    }
    .elevbtn8.elevbtn-active .top {
      stroke-dashoffset: -64px;
    }
    .elevbtn8.elevbtn-active .middle {
      transform: rotate(90deg);
    }
    .elevbtn8.elevbtn-active .bottom {
      stroke-dashoffset: -64px;
    }

    ${mobileMenuButtonFontFace}

    #${baseClassName}_mobile {
      position: fixed;
      display: block;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 10;
      margin-top: 0 !important;
      background: ${mobileMenuButtonBackgroundColor};
      text-align: center;
      padding: 5px 0;
      font-family: "${mobileMenuButtonFont}";
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 92;
    }

    #${baseClassName}_mobile_button {
      cursor: pointer;
      width: 40%;
      font-family: "${mobileMenuButtonFont}";
      ${mobileMenuButtonFontStyling};
      font-size: ${mobileMenuButtonFontSize}px;
      border: 0 solid transparent;
      border-radius: 0;
      padding: 10px 20px; /* todo: add user control */
      background: transparent;
      display: flex;
      align-items: center;
      justify-content: center;
      color: ${mobileMenuButtonTextColor};
    }

    #${baseClassName}_mobile_button svg {
      margin-right: 5px;
    }

    #${baseClassName}_accessibility_menu_pro_nav {
      display: flex !important;
    }

    @media(min-width: ${breakpointWidth}px) {
      #${baseClassName}_mobile {
        display: none;
      }     

      .${baseClassName}_accessibility_menu_pro_nav_wrapper_hidden_on_mobile {
        display: flex !important;
      }
    }
  `;
  document.head.appendChild(style);

  const bodyElement = document.body;
  bodyElement.classList.add("mobile_body_default");
}
