var fs = require('fs');
const archiver = require('archiver');

function zipDirectory(sourceDir, outPath) {
  const archive = archiver('zip', { zlib: { level: 9 }});
  const stream = fs.createWriteStream(outPath);

  return new Promise((resolve, reject) => {
    archive
      .directory(sourceDir, false)
      .on('error', err => reject(err))
      .pipe(stream)
    ;

    stream.on('close', () => resolve());
    archive.finalize();
  });
}
const archiveName = './litespeed-cache-debug.zip'
if( fs.existsSync(archiveName) ) fs.rmSync(archiveName);
zipDirectory('./litespeed-cache-debug/', archiveName)