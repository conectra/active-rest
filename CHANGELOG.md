# Changelog

Todas as modificações relevantes ao `active-rest` serão documentadas nesse arquivo seguindo o especificado em [KEEP CHANGELOG](http://keepachangelog.com/).

## 0.0.7 - 2018-03-17
@author marcuswubi

## Fixed
- Atualizado HasPost para convert o param do afterPost para array antes de chamar o método que extrai a chave.

## 0.0.6 - 2017-01-22
@author rafaelbeecker

## Updated
- Atualizado Hasfind adicionando filtro para nao exibir registros removidos por softDelete

## 0.0.5 - 2018-01-18
@author rafaelbeecker

## Updated
- Atualizado HasDel de modo a suportar recurso de delecao virtual de registro.

## 0.0.4 - 2018-01-04
@author marcuswubi

## Fixed
- Removi os try catchs circulando as operações, para utilização como biblioteca, é responsabilidade de quem utiliza controlar a exception.

## 0.0.3 - 2017-12-14
@author rafaelbeecker

## Added
- Adição das operações com transaction, para dar o rollback quando metodos de fluxo como afterPost e beforePost falharem.

## 0.0.2 - 2017-09-28
@author marcuswubi

## Added
- Adicionei helpers para conversoes e consumo dos schemas.

## 0.0.1 - 2017-09-25
@author marcuswubi

## Fixed
- Correções no composer, para resolver problemas na utilização como biblioteca.

## 0.0.0 - 2017-09-23
@author marcuswubi

## Forked
- Forkeado a partir de wubi/active-rest, em uma versão estável do projeto.