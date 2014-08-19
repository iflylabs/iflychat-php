<?php

global $_iflychat, $iflychat_settings;

/* Configure various settings of iFlyChat PHP Client here */

$iflychat_settings = array(

  /**
    * api_key - Get a valid API key from following instructions given here - https://iflychat.com/installation-php-client 
    */

  'api_key' => 'XXX',

  
  /**
    * base - The base url of your website 
    */
  
  'base' => 'http://localhost',

  
  /**
    * path - The relative path from home or base URL to where this file is present 
    */  
  
  'path' => '/iflychat-php/',

  
  /** 
    * theme - The theme of the chat. Valid values are 'light' and 'dark'. 
    */  
  
  'theme' => 'light',

  
  /**
    * user_picture - Display user avatars or pictures. Valid values are TRUE and FALSE. 
    */
  
  'user_picture' => TRUE,

  
  /**
    * notification sound - Enable sound notification by default. Valid values are TRUE and FALSE. 
    */  
  
  'notification_sound' => TRUE,

  
  /**
    * enable_smileys - Enable emoticons. 
    * Valid values are TRUE and FALSE. 
    */
  
  'enable_smileys' => TRUE,


  /**
    * enable_mobile_browser_app - Enable browser based Mobile app.
    * Valid values are TRUE and FALSE. 
    */
  
  'enable_mobile_browser_app' => TRUE,


  /**
    * log_chat - Enable logging of chat messages. Valid values are TRUE and FALSE. 
    */
  
  'log_chat' => TRUE,


  /**
    * anon_prefix - Prefix to be used with anonymous users (4 to 7 characters) 
    */
  
  'anon_prefix' => 'Guest',


  /**
    * anon_use_name - Select whether to use random generated name or number to assign to a new anonymous user 
    * Valid values are TRUE and FALSE
    * TRUE - Use random names
    * FALSE - Use random numbers   
    */
  
  'anon_use_name' => TRUE,

  /**
    * public_chatroom - Enable public chatroom. 
    * Valid values are TRUE and FALSE. 
    */
  
  'public_chatroom' => TRUE,
  

  /**
    * allow_user_font_color - Specify whether to allow users to set color of their name in a room
    *
    * Valid values are TRUE and FALSE
    **/
  
  'allow_user_font_color' => TRUE,


  /**
    * chat_topbar_color - Chat Top Bar Color
    */
  
  'chat_topbar_color' => '#222222',
  

  /**
    * chat_topbar_text_color - Chat Top Bar Text Color
    */

  'chat_topbar_text_color' => '#FFFFFF',


  /**
    * chat_font_color - Chat Font Color
    */

  'chat_font_color' => '#222222',


  /**
    * public_chatroom_header - Public Chatroom Header
    */

  'public_chatroom_header' => 'Public Chatroom',
  

  /**
    * chat_list_header - Chat List Header
    */

  'chat_list_header' => 'Chat',
  

  /**
    * stop_word_list - Stop Words (separated by comma)
    */
  
  'stop_word_list' => 'asshole,assholes,bastard,beastial,beastiality,beastility,bestial,bestiality,bitch,bitcher,bitchers,bitches,bitchin,bitching,blowjob,blowjobs,bullshit,clit,cock,cocks,cocksuck,cocksucked,cocksucker,cocksucking,cocksucks,cum,cummer,cumming,cums,cumshot,cunillingus,cunnilingus,cunt,cuntlick,cuntlicker,cuntlicking,cunts,cyberfuc,cyberfuck,cyberfucked,cyberfucker,cyberfuckers,cyberfucking,damn,dildo,dildos,dick,dink,dinks,ejaculate,ejaculated,ejaculates,ejaculating,ejaculatings,ejaculation,fag,fagging,faggot,faggs,fagot,fagots,fags,fart,farted,farting,fartings,farts,farty,felatio,fellatio,fingerfuck,fingerfucked,fingerfucker,fingerfuckers,fingerfucking,fingerfucks,fistfuck,fistfucked,fistfucker,fistfuckers,fistfucking,fistfuckings,fistfucks,fuck,fucked,fucker,fuckers,fuckin,fucking,fuckings,fuckme,fucks,fuk,fuks,gangbang,gangbanged,gangbangs,gaysex,goddamn,hardcoresex,horniest,horny,hotsex,jism,jiz,jizm,kock,kondum,kondums,kum,kumer,kummer,kumming,kums,kunilingus,lust,lusting,mothafuck,mothafucka,mothafuckas,mothafuckaz,mothafucked,mothafucker,mothafuckers,mothafuckin,mothafucking,mothafuckings,mothafucks,motherfuck,motherfucked,motherfucker,motherfuckers,motherfuckin,motherfucking,motherfuckings,motherfucks,niger,nigger,niggers,orgasim,orgasims,orgasm,orgasms,phonesex,phuk,phuked,phuking,phukked,phukking,phuks,phuq,pis,piss,pisser,pissed,pisser,pissers,pises,pisses,pisin,pissin,pising,pissing,pisof,pissoff,porn,porno,pornography,pornos,prick,pricks,pussies,pusies,pussy,pusy,pussys,pusys,slut,sluts,smut,spunk',
  

  /**
    * use_stop_word_list - Specify how to use Stop Words to filter chat
    * 
    * Valid values are defined below:
    * 1 - Don't filter
    * 2 - Filter in public chatroom
    * 3 - Filter in private chats
    * 4 - Filter in all rooms 
    */  

  'use_stop_word_list' => '1',
  

  /**
    * stop_links - Specify whether to allow/block hyperlinks posted in chats
    *
    * Valid values are defined below:
    *
    * 1 - Don't block
    * 2 - Block in public chatroom
    * 3 - Block in private chats
    * 4 - Block in all rooms
    */
  
  'stop_links' => '1',
  

  /**
    * allow_anon_links - Specify whether to apply above defined hyperlinks setting only to unregistered users
    * Valid values are TRUE and FALSE
    */
  
  'allow_anon_links' => FALSE,


  /**
    * allow_render_images - Specify whether to render image and video hyperlinks inline in chat
    * Valid values are TRUE and FALSE
    */
  
  'allow_render_images' => TRUE,


  /**
    * enable_file_attachment - Specify whether to allow user to share/upload file in chat
    *
    * Valid values are TRUE and FALSE
    */
  
  'enable_file_attachment' => TRUE,


  /**
   * allow_single_message_delete - Specify whether to allow users to delete messages selectively when in private conversation
   *
   * Valid values are defined below:
   * 1 - Allow all users
   * 2 - Allow only moderators
   * 3 - Disable
   */
  
  'allow_single_message_delete' => '1',


  /**
   * allow_single_message_delete - Specify whether to allow users to clear all messages in a room
   *
   * Valid values are defined below:
   * 1 - Allow all users
   * 2 - Allow only moderators
   * 3 - Disable
   */

  'allow_clear_room_history' => '1',


  /**
   * chat_type - Specify which chat version to use
   *
   * Valid values are defined below:
   * 1 - Community Chat
   * 2 - Support Chat
   */
  'chat_type' => '1',

  'enable_user_group_filtering' => FALSE,
  
  'enable_user_relationships' => FALSE,
  
  'load_async' => TRUE,
  
  'ajax_file' => 'ajax-chat.php',

  'mobile_file' => 'mobile-chat.php',
  
  /**
    * minimize_chat_user_list
    */
  
  'minimize_chat_user_list' => FALSE,


);

