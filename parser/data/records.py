import sqlite3


class AdLink:

    _table_exists = False

    def __init__(self, id_: int = 0, url: str = ''):
        self.id_ = id_
        self.url = url
        self._table_name = 'ad_links'
        self._database_name = 'links.db'
        if not self._table_exists:
            self._create_table()
            self._table_exists = True

    def _execute(self, query):
        con = sqlite3.connect(self._database_name)
        results = con.cursor().execute(query).fetchall()
        con.commit()
        con.close()
        return results

    def _create_table(self):
        query = f'''
            create table if not exists {self._table_name} (
                id integer primary key,
                link text not null
            )'''
        self._execute(query)

    def create(self, url: str) -> None:
        query = f'insert into {self._table_name} (link) values (\'{url}\')'
        self._execute(query)

    def all(self) -> list:
        query = f'select id, link from {self._table_name}'
        results = self._execute(query)
        links = []

        for result in results:
            links.append(AdLink(result[0], result[1]))

        return links

    def __str__(self):
        return f'{self.id_} : {self.url}'
