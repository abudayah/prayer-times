const folderId = '13UtAQBBptXKEk1ANAfNKXuWIytwl4xOD'; // Replace with your folder ID
const apiKey = 'AIzaSyDOXpLfKntDggBRQAjFA1RQl_rSHm9PVvk'; // Replace with your API key

async function getImagesFromGoogleDrive(folderId, apiKey) {
  const baseUrl = 'https://www.googleapis.com/drive/v3/files';
  const query = `'${folderId}' in parents and mimeType contains 'image/'`;

  try {
    const response = await fetch(`${baseUrl}?q=${encodeURIComponent(query)}&key=${apiKey}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    if (data.files) {
      return data.files.map(file => ({
        id: file.id,
        name: file.name,
        mimeType: file.mimeType,
        webContentLink: file.webContentLink, //direct download link
        thumbnailLink: file.thumbnailLink,
        webViewLink: file.webViewLink, //google drive viewer link
      }));
    } else {
      return []; // No files found
    }
  } catch (error) {
    console.error('Error fetching images:', error);
    return [];
  }
}


getImagesFromGoogleDrive(folderId, apiKey).then(images => {
  console.log('Images:', images);
  // You can now use the 'images' array in your application
  if(images.length > 0){
    images.forEach(image=>{
      console.log(image.name, image.webContentLink);
      //create image elements here.
    });
  }
});