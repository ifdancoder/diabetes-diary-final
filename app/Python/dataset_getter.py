import argparse
import os
import pandas as pd
import json
from ai import DiabetesModel

this_folder = os.path.dirname(__file__)
experiments_folder = os.path.join(this_folder, 'experiments')

def fill_sugar_levels(df, fillingtype):
    if fillingtype == 0:
        df['sugar_level'] = df['sugar_level'].interpolate()
    else:
        df['sugar_level'] = df['sugar_level'].interpolate(method='polynomial', order=2)
    df['sugar_level'] = df['sugar_level'].round(2)
    return df

def prepare_columns(df):
    df['sugar_level'] = df['sugar_level'].astype(float)
    df['cannula_changing_status'] = df['cannula_changing_status'].astype(int)
    df['desease_status'] = df['desease_status'].astype(int)
    df['fast_chs'] = df['fast_chs'].astype(float)
    df['insulin'] = df['insulin'].astype(float)
    df['long_chs'] = df['long_chs'].astype(float)
    df['middle_chs'] = df['middle_chs'].astype(float)
    df['minutes'] = df['minutes'].astype(int)
    df['physical_activity_intensity'] = df['physical_activity_intensity'].astype(int)
    df['sleeping_status'] = df['sleeping_status'].astype(int)
    df['stress_level'] = df['stress_level'].astype(int)
    return df

def main(user_id, experiment_id, fillingtype):
    data_file = os.path.join(experiments_folder, f'{experiment_id}.json')
    data = None
    with open(data_file) as file:
        data = json.load(file)

    datetimes = data['datetimes']

    crop_to1 = datetimes['first_cgm']
    crop_to2 = datetimes['start']
    prediction_from = datetimes['predict']

    df = pd.DataFrame.from_records(data['data'])
    df['datetime'] = pd.to_datetime(df['datetime'])
    df = df.set_index('datetime')

    df = prepare_columns(df)
    df = df[crop_to1:]
    df = fill_sugar_levels(df, fillingtype)
    df = df[crop_to2:]
    
    df.reset_index(drop=True, inplace=True)

    model = DiabetesModel(user_id)
    results = model.test_predict_multiple(df)

    results_list = [arr[0] for arr in results]

    os.system('clear')
    print(results_list, end='')

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('user_id', type=int, help='Уникальный идентификатор пользователя')
    parser.add_argument('experiment_id', type=int, help='Уникальный идентификатор эксперимента')
    parser.add_argument('interpolation_type', default=1, type=int, help='Уникальный идентификатор эксперимента')
    
    args = parser.parse_args()
    main(args.user_id, args.experiment_id, args.interpolation_type)