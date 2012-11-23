<?php

global $_iflychat, $iflychat;
/* Configure various settings of iFlyChat PHP Client here */
$iflychat = array(
/* Get a valid API key from following instructions given here - https://iflychat.com/installation-php-client */
  'api_key' => 'XXXXXXX',
/* The relative path from home URL to where this file is present */  
  'path' => '/iflychat-php/',
  'base' => (($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'],
  'theme' => 'light',
  'user_picture' => TRUE,
  'notification_sound' => TRUE,
  'enable_smileys' => TRUE,
  'log_chat' => TRUE,
  'anon_prefix' => 'Guest',
  'anon_use_name' => TRUE,
  'anon_name_set' => 'usa',
  'public_chatroom' => TRUE,
  'chat_top_bar_color' => '#222222',
  'chat_top_bar_text_color' => '#FFFFFF',
  'chat_font_color' => '#222222',
  'public_chatroom_header' => 'Public Chatroom',
  'chat_list_header' => 'Chat',
  'stop_word_list' => 'asshole,assholes,bastard,beastial,beastiality,beastility,bestial,bestiality,bitch,bitcher,bitchers,bitches,bitchin,bitching,blowjob,blowjobs,bullshit,clit,cock,cocks,cocksuck,cocksucked,cocksucker,cocksucking,cocksucks,cum,cummer,cumming,cums,cumshot,cunillingus,cunnilingus,cunt,cuntlick,cuntlicker,cuntlicking,cunts,cyberfuc,cyberfuck,cyberfucked,cyberfucker,cyberfuckers,cyberfucking,damn,dildo,dildos,dick,dink,dinks,ejaculate,ejaculated,ejaculates,ejaculating,ejaculatings,ejaculation,fag,fagging,faggot,faggs,fagot,fagots,fags,fart,farted,farting,fartings,farts,farty,felatio,fellatio,fingerfuck,fingerfucked,fingerfucker,fingerfuckers,fingerfucking,fingerfucks,fistfuck,fistfucked,fistfucker,fistfuckers,fistfucking,fistfuckings,fistfucks,fuck,fucked,fucker,fuckers,fuckin,fucking,fuckings,fuckme,fucks,fuk,fuks,gangbang,gangbanged,gangbangs,gaysex,goddamn,hardcoresex,horniest,horny,hotsex,jism,jiz,jizm,kock,kondum,kondums,kum,kumer,kummer,kumming,kums,kunilingus,lust,lusting,mothafuck,mothafucka,mothafuckas,mothafuckaz,mothafucked,mothafucker,mothafuckers,mothafuckin,mothafucking,mothafuckings,mothafucks,motherfuck,motherfucked,motherfucker,motherfuckers,motherfuckin,motherfucking,motherfuckings,motherfucks,niger,nigger,niggers,orgasim,orgasims,orgasm,orgasms,phonesex,phuk,phuked,phuking,phukked,phukking,phuks,phuq,pis,piss,pisser,pissed,pisser,pissers,pises,pisses,pisin,pissin,pising,pissing,pisof,pissoff,porn,porno,pornography,pornos,prick,pricks,pussies,pusies,pussy,pusy,pussys,pusys,slut,sluts,smut,spunk',
  'use_stop_word_list' => '1',
  'stop_links' => '1',
  'allow_anon_links' => TRUE,
  'show_admin_list' => FALSE,
  
);

$_iflychat = array (
  'HOST' => 'http://api.iflychat.com',
  'A_HOST' => 'https://api.iflychat.com',
  'PORT' => 80,
  'A_PORT' => 443,
);

