function openWindowsOnIframe(){
   var referrer   = document.referrer;

   if( ! referrer ){
      return;
   }

   var inputs     = document.querySelectorAll("a");
   
   for( var i = 0; i < inputs.length; i++ ){
      inputs[i].addEventListener("click", function(e){
         var a = this;
         window.open( a.getAttribute( 'href' ), '_blank' );
      });
   }   
}

openWindowsOnIframe();