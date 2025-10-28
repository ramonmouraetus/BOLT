/**
 * Script para gerar o ZIP do tema
 * Pode ser executado com: node scripts/generate-zip.js [ambiente]
 */

const fs = require('fs');
const path = require('path');
const del = require('del');
const archiver = require('archiver');

// Obter versão do tema
function getVersion() {
  const package = fs.readFileSync(path.resolve(__dirname, '../package.json'), 'utf8');
  return JSON.parse(package).version;
}

// Obter changelog formatado
function getVersionChangelog() {
  try {
    const VERSION = getVersion();
    const file = fs.readFileSync(path.resolve(__dirname, '../CHANGELOG.md'), 'utf8');
    const separatedVersions = file.split(/\[\d\.\d\.\d\]/);
    if (!separatedVersions || separatedVersions.length < 2) {
      console.warn('Formato do CHANGELOG.md não reconhecido. Usando formato simples.');
      return `<h3>Version ${VERSION}</h3>`;
    }
    
    const currentVersionCL = separatedVersions[1];
    let replaced = currentVersionCL.replaceAll(/\(.*?\)/ig, "").replaceAll(')', '');
    
    const h3Matches = replaced.match(/(### =?).*/g);
    if (h3Matches) {
      h3Matches.forEach(piece => {
        const extracted = piece.replace('### ', '');
        replaced = replaced.replace(piece, `<h4>${extracted}</h4>`);
      });
    }

    const liMatches = replaced.match(/(\* =?).*/g);
    if (liMatches) {
      liMatches.forEach((piece) => {
        const extracted = piece.replace('* ', '');
        replaced = replaced.replace(piece, `<li>${extracted}</li>`);
      });
    }

    replaced = replaced.replaceAll(/\r|\n/g, "");
    replaced = replaced.replaceAll('</h4><li>', '</h4><ul><li>');
    replaced = replaced.replaceAll('</li><h4>', '</li></ul><h4>');
    replaced = replaced.replaceAll('<h4></h4>', '');
    
    const releaseDateMatch = file.match(/\(.{4}\-.{2}\-.{2}\)/);
    let releaseDate = '';
    if (releaseDateMatch && releaseDateMatch.length > 0) {
      releaseDate = releaseDateMatch[0].replace(/(\(|\))/g, '');
    } else {
      releaseDate = new Date().toISOString().split('T')[0];
    }
    
    return `<img style="max-width: 100%;" src="bolt-2.jpg"/><h3>Version ${VERSION} - ${releaseDate}</h3>${replaced}`;
  } catch (error) {
    console.error('Erro ao processar o changelog:', error);
    return `<h3>Version ${getVersion()}</h3>`;
  }
}

// Alterar versão em arquivos
function changeVersionInFiles(fileNames = []) {
  const VERSION = getVersion();
  for (const fileName of fileNames) {
    const filePath = path.resolve(__dirname, '..', fileName);
    if (fs.existsSync(filePath)) {
      try {
        const themeMainFile = fs.readFileSync(filePath, 'utf8');
        console.log('Current version =>', VERSION);
        fs.writeFileSync(filePath, themeMainFile.replace(/\b\d+\.\d+\.\d+\b/g, VERSION));
      } catch (error) {
        console.error(`Erro ao atualizar versão no arquivo ${fileName}:`, error);
      }
    } else {
      console.warn(`Arquivo ${fileName} não encontrado para atualização de versão.`);
    }
  }
}

// Gerar informações do tema
function generateThemeInfo(env = '') {
  try {
    const VERSION = getVersion();
    const THEME_NAME = "bolt";
    const GCLOUD_ENV = process.env.GCLOUD_ENV || '';
    const CLOUD_FILES_PATH = process.env.CLOUD_FILES_PATH || `https://assets${GCLOUD_ENV}.brius.com.br/themes/${THEME_NAME}/`;
    
    changeVersionInFiles(['./style.css', './includes/theme-functions/core/globals.php']);
    
    // Ler arquivo de exemplo
    const themeInfoPath = path.resolve(__dirname, '../src/theme-info.example.json');
    if (!fs.existsSync(themeInfoPath)) {
      console.error('Arquivo theme-info.example.json não encontrado. Gerando um novo.');
      const defaultInfo = {
        theme: "bolt",
        new_version: VERSION,
        requires: "5.0",
        requires_php: "7.0"
      };
      const distDir = path.resolve(__dirname, '../dist');
      if (!fs.existsSync(distDir)) {
        fs.mkdirSync(distDir, { recursive: true });
      }
      const filename = `${THEME_NAME}-info.json`;
      fs.writeFileSync(path.resolve(distDir, filename), JSON.stringify(defaultInfo, null, 4));
      
      // Tentar atualizar o arquivo de opções
      try {
        const updateFilePath = path.resolve(__dirname, '../includes/theme-configs/data/brius_theme_update_options.json');
        if (fs.existsSync(updateFilePath)) {
          const updateFile = fs.readFileSync(updateFilePath, 'utf8');
          const updateInfo = JSON.parse(updateFile);
          updateInfo.bolt_update_url = `${CLOUD_FILES_PATH}${filename}`;
          fs.writeFileSync(updateFilePath, JSON.stringify(updateInfo, null, 4));
        }
      } catch (error) {
        console.warn('Não foi possível atualizar o arquivo de opções de atualização:', error.message);
      }
      
      return filename;
    }
    
    const file = fs.readFileSync(themeInfoPath, 'utf8');
    let infoData;
    try {
      infoData = JSON.parse(file);
    } catch (error) {
      console.error('Erro ao analisar o arquivo theme-info.example.json:', error);
      infoData = {
        theme: "bolt",
        requires: "5.0",
        requires_php: "7.0"
      };
    }
    
    // Criar novas informações
    const newInfo = {
      new_version: VERSION,
      package: `${CLOUD_FILES_PATH}${THEME_NAME}${VERSION}.zip`,
      url: `${CLOUD_FILES_PATH}changelog.html`,
      last_updated: new Date().toISOString(),
      sections: {
        description: "This plugin enable Brius for Publishers (BFP) to show Ads in Wordpress.",
        installation: "Click the activate/install button and that's it."
      },
      icons: {
        "2x": "https://assets.brius.com.br/themes/bolt/bolt-icon.png",
        "1x": "https://assets.brius.com.br/themes/bolt/bolt-icon-2.png"
      },
      banners: {
        low: "https://assets.brius.com.br/themes/bolt/bolt.jpg",
        high: "https://assets.brius.com.br/themes/bolt/bolt-2.jpg"
      }
    };
    
    // Mesclar informações
    const mergedInfo = {
      ...infoData,
      ...newInfo
    };
    
    // Salvar arquivo
    const filename = `${THEME_NAME}-info.json`;
    const distDir = path.resolve(__dirname, '../dist');
    
    if (!fs.existsSync(distDir)) {
      fs.mkdirSync(distDir, { recursive: true });
    }
    
    fs.writeFileSync(path.resolve(distDir, filename), JSON.stringify(mergedInfo, null, 4));
    
    // Atualizar URL no arquivo de opções
    try {
      const updateFilePath = path.resolve(__dirname, '../includes/theme-configs/data/brius_theme_update_options.json');
      if (fs.existsSync(updateFilePath)) {
        const updateFile = fs.readFileSync(updateFilePath, 'utf8');
        const updateInfo = JSON.parse(updateFile);
        updateInfo.bolt_update_url = `${CLOUD_FILES_PATH}${filename}`;
        fs.writeFileSync(updateFilePath, JSON.stringify(updateInfo, null, 4));
      } else {
        console.warn('Arquivo de opções de atualização não encontrado.');
      }
    } catch (error) {
      console.warn('Erro ao atualizar o arquivo de opções:', error.message);
    }
    
    return filename;
  } catch (error) {
    console.error('Erro ao gerar informações do tema:', error);
    return `${THEME_NAME}-info.json`;
  }
}

// Copiar pastas para o diretório temporário
function copyFolders() {
  const THEME_NAME = "bolt";
  const rootDir = path.resolve(__dirname, '..');
  const themeTempDir = path.resolve(rootDir, THEME_NAME);
  
  // Limpar diretório temporário se já existir
  if (fs.existsSync(themeTempDir)) {
    try {
      del.sync(themeTempDir);
    } catch (error) {
      console.error('Erro ao limpar diretório temporário:', error);
    }
  }
  
  // Criar diretório temporário
  try {
    fs.mkdirSync(themeTempDir, { recursive: true });
  } catch (error) {
    console.error('Erro ao criar diretório temporário:', error);
    throw error;
  }
  
  // Pastas para copiar
  const myFolders = ['vendor', 'includes'];
  
  for (const folder of myFolders) {
    const sourcePath = path.resolve(rootDir, folder);
    const destPath = path.resolve(themeTempDir, folder);
    
    // Skip if folder doesn't exist
    if (!fs.existsSync(sourcePath)) {
      console.warn(`Pasta ${folder} não encontrada.`);
      continue;
    }
    
    try {
      // Usar fs.cpSync para Node.js >= 16.7.0
      if (typeof fs.cpSync === 'function') {
        fs.cpSync(sourcePath, destPath, { recursive: true });
      } else {
        // Implementação alternativa para versões mais antigas do Node.js
        copyFolderRecursiveSync(sourcePath, themeTempDir);
      }
    } catch (error) {
      console.error(`Erro ao copiar pasta ${folder}:`, error);
    }
  }
  
  return themeTempDir;
}

// Função auxiliar para copiar pastas recursivamente em versões antigas do Node.js
function copyFolderRecursiveSync(source, target) {
  try {
    // Verificar se o diretório de destino existe
    const targetFolder = path.join(target, path.basename(source));
    if (!fs.existsSync(targetFolder)) {
      fs.mkdirSync(targetFolder, { recursive: true });
    }

    // Copiar conteúdo
    if (fs.lstatSync(source).isDirectory()) {
      const files = fs.readdirSync(source);
      files.forEach(function(file) {
        const curSource = path.join(source, file);
        if (fs.lstatSync(curSource).isDirectory()) {
          copyFolderRecursiveSync(curSource, targetFolder);
        } else {
          fs.copyFileSync(curSource, path.join(targetFolder, file));
        }
      });
    }
  } catch (error) {
    console.error('Erro ao copiar diretório:', error);
  }
}

// Criar arquivo de changelog
function createChangelogFile() {
  try {
    const CHANGELOG = getVersionChangelog();
    const distDir = path.resolve(__dirname, '../dist');
    
    if (!fs.existsSync(distDir)) {
      fs.mkdirSync(distDir, { recursive: true });
    }
    
    fs.writeFileSync(path.resolve(distDir, 'changelog.html'), CHANGELOG);
    console.log('Arquivo de changelog criado com sucesso.');
  } catch (error) {
    console.error('Erro ao criar arquivo de changelog:', error);
  }
}

// Função principal para gerar o ZIP
async function generateZip(envArg) {
  console.log('Iniciando processo de geração do ZIP...');
  
  const VERSION = getVersion();
  const THEME_NAME = "bolt";
  const env = envArg ? `-${envArg}` : '';
  const rootDir = path.resolve(__dirname, '..');
  const distDir = path.resolve(rootDir, 'dist');
  const themeTempDir = path.resolve(rootDir, THEME_NAME);
  
  // Garantir que o diretório de saída exista
  if (!fs.existsSync(distDir)) {
    fs.mkdirSync(distDir, { recursive: true });
  }
  
  console.log(`Gerando ZIP para o tema ${THEME_NAME} versão ${VERSION}${env}...`);
  
  try {
    // Gerar informações do tema
    console.log('Gerando informações do tema...');
    generateThemeInfo(envArg);
    
    // Copiar pastas para o diretório temporário
    console.log('Copiando pastas para o diretório temporário...');
    copyFolders();
    
    // Copiar arquivos PHP
    console.log('Copiando arquivos PHP...');
    try {
      fs.readdirSync(rootDir).forEach(file => {
        if (file.endsWith('.php')) {
          fs.copyFileSync(path.resolve(rootDir, file), path.resolve(themeTempDir, file));
        }
      });
    } catch (error) {
      console.error('Erro ao copiar arquivos PHP:', error);
    }
    
    // Copiar screenshot
    console.log('Copiando screenshot...');
    const screenshotPath = path.resolve(rootDir, 'screenshot.png');
    if (fs.existsSync(screenshotPath)) {
      fs.copyFileSync(screenshotPath, path.resolve(themeTempDir, 'screenshot.png'));
    } else {
      console.warn('Arquivo screenshot.png não encontrado.');
    }
    
    // Copiar style.css
    console.log('Copiando style.css...');
    const styleCssPath = path.resolve(rootDir, 'style.css');
    if (fs.existsSync(styleCssPath)) {
      fs.copyFileSync(styleCssPath, path.resolve(themeTempDir, 'style.css'));
    } else {
      console.warn('Arquivo style.css não encontrado.');
    }
    
    // Copiar arquivos de recursos se existirem
    console.log('Copiando arquivos de recursos...');
    const resourcesDir = path.resolve(rootDir, 'src/resources');
    if (fs.existsSync(resourcesDir)) {
      try {
        const files = fs.readdirSync(resourcesDir);
        files.forEach(file => {
          fs.copyFileSync(path.resolve(resourcesDir, file), path.resolve(distDir, file));
        });
      } catch (error) {
        console.error('Erro ao copiar arquivos de recursos:', error);
      }
    } else {
      console.warn('Diretório src/resources não encontrado.');
    }
    
    // Criar arquivo ZIP
    console.log('Criando arquivo ZIP...');
    
    return new Promise((resolve, reject) => {
      try {
        const zipFilePath = path.resolve(distDir, `${THEME_NAME}${VERSION}${env}.zip`);
        const output = fs.createWriteStream(zipFilePath);
        const archive = archiver('zip', {
          zlib: { level: 9 } // Nível máximo de compressão
        });
        
        // Lidar com eventos do archive
        archive.on('error', function(err) {
          console.error('Erro ao criar arquivo ZIP:', err);
          reject(err);
        });
        
        // Finalizar o arquivo zip
        output.on('close', function() {
          console.log(`Arquivo ZIP criado: ${THEME_NAME}${VERSION}${env}.zip (${archive.pointer()} bytes)`);
          console.log('Criando arquivo de changelog...');
          createChangelogFile();
          
          // Remover diretório temporário
          console.log('Removendo arquivos temporários...');
          setTimeout(() => {
            try {
              del.sync(themeTempDir);
              console.log('Processo concluído com sucesso!');
              resolve(zipFilePath);
            } catch (error) {
              console.error('Erro ao limpar arquivos temporários:', error);
              resolve(zipFilePath); // Ainda resolvemos com o arquivo ZIP, mesmo que a limpeza falhe
            }
          }, 1000);
        });
        
        // Pipe arquivo e conteúdo
        archive.pipe(output);
        archive.directory(themeTempDir, false);
        archive.finalize();
      } catch (error) {
        console.error('Erro na criação do arquivo ZIP:', error);
        reject(error);
      }
    });
  } catch (error) {
    console.error('Erro geral ao gerar o arquivo ZIP:', error);
    throw error;
  }
}

// Verificar se o script está sendo executado diretamente
if (require.main === module) {
  // Obter o ambiente dos argumentos da linha de comando
  const envArg = process.argv[2];
  
  // Executar a geração do ZIP
  generateZip(envArg)
    .then((zipFilePath) => {
      console.log(`ZIP gerado com sucesso: ${zipFilePath}`);
      process.exit(0);
    })
    .catch(err => {
      console.error('Erro ao gerar ZIP:', err);
      process.exit(1);
    });
} else {
  // Exportar a função para uso em outros scripts
  module.exports = generateZip;
} 