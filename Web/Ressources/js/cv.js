const options = {
  margin: 1,
  filename: 'cv.pdf',
  image: { 
    type: 'jpeg', 
    quality: 0.98 
  },
  html2canvas: { 
    scale: 2 
  },
  jsPDF: { 
    unit: 'in', 
    format: 'letter', 
    orientation: 'portrait' ,
     compressPDF: true
  }
}

$('.btn-download').click(function(e){
  e.preventDefault();
  const element = document.getElementById('cv-candidat');
  html2pdf().from(element).set(options).save();
});
