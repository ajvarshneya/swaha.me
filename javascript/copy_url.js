var copyBtn = document.querySelector('#js-copy-button');

copyBtn.addEventListener('click', function(event) {
  var copy = document.querySelector('#url-form');
  copy.select();

  try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    console.log('Copying was ' + msg + '.');
  } catch (err) {
    console.log('Error, failed to copy.');
  }
});