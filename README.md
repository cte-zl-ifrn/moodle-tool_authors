# Moodle Authors Tracker Plugin

Plugin Moodle que rastreia autoria intelectual inalienável de atividades/recursos conforme Lei Brasileira. Registra todas criações/edições/exclusões por editingteachers em histórico permanente.

## Componentes

Este plugin consiste em três componentes integrados:

1. **Admin Tool (tool_authors)**: Ferramenta de administração principal com página dedicada
2. **Filter (filter_authors)**: Filtro para exibir autores inline usando shortcodes
3. **Block (block_authors)**: Bloco para exibir autores em cursos

## Recursos

### Rastreamento Automático
- Monitora eventos de criação, atualização e exclusão de módulos do curso
- Registra automaticamente o usuário, timestamp e tipo de ação
- Mantém histórico permanente mesmo após exclusão de módulos ou usuários

### Página Dedicada
Acesso via navegação do curso para usuários com permissão `tool/authors:viewpage`

**Aba 1: Autores Atuais**
- Lista módulos com seus respectivos autores
- Calcula porcentagem de contribuição: (ações_autor ÷ total_ações_módulo) × 100
- Exibe foto e informações do perfil dos autores
- Mostra links para perfis externos (Lattes, LinkedIn) via custom profile fields

**Aba 2: Histórico Completo**
- Timeline completa de todas as ações no curso
- Filtros por módulo, usuário, tipo de ação
- Exportação de dados

### Filter (Sintaxe)
Use `{authors:cmid123}` em qualquer texto do curso para exibir automaticamente os autores do módulo.

### Block
- Configurável para mostrar autores de um módulo específico ou resumo do curso
- Limite configurável de módulos a exibir
- Respeita permissões de visualização

## Capabilities

- `tool/authors:viewpage` - Ver página dedicada (editingteachers)
- `tool/authors:viewblock` - Ver block (editingteachers)
- `tool/authors:viewfilter` - Ver filter (qualquer usuário no curso)
- `tool/authors:manage` - Editar registros (managers+)
- `tool/authors:admin` - Configurar plugin (admins)

## Backup/Restore

- **Backup**: Salva histórico completo do curso
- **Restore**: CONCATENA ao histórico existente (não sobrescreve)
- Preserva autores originais em importações de módulos

## Configurações

- **Porcentagem mínima**: Percentual mínimo para exibir autor (padrão: 1%)
- **Profile fields**: Links personalizados (Lattes, LinkedIn) via custom user profile fields

## Instalação

1. Descompacte o arquivo na pasta `admin/tool/authors/`
2. Descompacte o filter na pasta `filter/authors/`
3. Descompacte o block na pasta `blocks/authors/`
4. Acesse Site Administration > Notifications para instalar
5. Configure permissões conforme necessário

## Requisitos

- Moodle 4.5 ou superior
- PHP 8.2 ou superior
- PostgreSQL 13+ ou MySQL 8.0+

## Conformidade Legal

Este plugin foi desenvolvido para atender aos requisitos da Lei Brasileira de Direitos Autorais, mantendo registro permanente e inalienável da autoria intelectual de conteúdos educacionais.

## Licença

GPL v3 ou posterior

## Créditos

Copyright 2026 CTE-ZL-IFRN
