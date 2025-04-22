var fs = require('fs');
const archiver = require('archiver');

function zipDirectory(sourceDir, outPath) {
  const archive = archiver('zip', { zlib: { level: 9 }});
  const stream = fs.createWriteStream(outPath);

  return new Promise((resolve, reject) => {
    archive
      .directory('./' + sourceDir + '/', sourceDir)
      .on('error', err => reject(err))
      .pipe(stream)
    ;

    stream.on('close', () => resolve());
    archive.finalize();
  });
}


const pluginDir = 'litespeed-cache-debug';
const archiveName = './' + pluginDir + '.zip';
if( fs.existsSync(archiveName) ) fs.rmSync(archiveName);
zipDirectory(pluginDir, archiveName)